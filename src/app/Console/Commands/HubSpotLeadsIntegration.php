<?php

namespace App\Console\Commands;

use App\Services\CRM\HubSpot as HubspotService;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use App\Models\Donor;
use Illuminate\Support\Facades\DB;
use App\Types\DonationTypes;

class HubSpotLeadsIntegration extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'hubspot:send-leads';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send Chosen leads to HubSpot';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
        $this->hubspotService = new HubspotService();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $this->log('info','-----------------------');
        $this->log('info',':: Starting HubSpot Integration ::');

        if ( config('hubspot.integration_status') != 'enabled' ) {
            $this->log('warn','HUBSPOT_INTEGRATION_STATUS is not enabled');
            return 0;
        }

        $leads = $this->getLeads();
        if ( empty($leads) ) {
            $this->log('warn','Availables leads not found');
            $this->log('info',':: Finish HubSpot Integration ::');
            return 0;
        }

        $this->process($leads);

        $this->log('info',':: Finish HubSpot Integration ::');
        $this->log('info','-----------------------');
    }

    private function getLeads(): array
    {
        $interval = config('hubspot.time_interval');

        $now_date = Carbon::now();
        $now_date->subHours($interval);

        return DB::select(DB::raw("
            select
                donors.id,
                donors.name,
                donors.email,
                donors.phone,
                donors.media_source,
                has_paid_donation
            from
            (select
                donors.id,
                donors.name,
                donors.email,
                donors.phone,
                donors.hubspot_lead,
                ( select count(id) from donations
                    where donor_id = donors.id and donation_status = '".DonationTypes::PAID."' limit 1 )
                    as 'has_paid_donation',
                ( select media_source from donor_medias
                    where donor_id = donors.id order by created_at desc limit 1)
                    as media_source,
                donations.donation_status,
                donations.created_at
            from donors
            left join donations ON donors.id = donations.donor_id
            where hubspot_lead is null
                and donations.donation_status <> '".DonationTypes::PAID."'
                and donations.created_at < '".($now_date->format('Y-m-d H:i:s'))."') as donors

        where donors.has_paid_donation < 1
        group by donors.id;"));
    }

    private function process(array $leads): void
    {
        $this->log('info',count($leads).' available leads found');

        try {
            foreach ( $leads as $index => $lead ) {
                $this->log('info',' Processing ['. ($index+1).'/'. count($leads). '] lead.');

                $hubspot_donor = null;

                $contact_exists = $this->hubspotService->checkIfContactExistsByEmail($lead->email);
                if ( $contact_exists ) {
                    $this->log('info','['. ($index+1).'/'. count($leads). ']['.$lead->email.'] Donor already registered on Hubspot');
                    $hubspot_donor = $this->hubspotService->getContactByEmail($lead->email);

                } else {
                    $hubspot_donor = $this->createContact($lead);
                }

                if ( $hubspot_donor ) {
                    $this->addContactToList($hubspot_donor, $lead);

                    $this->updateDonor($lead);
                }
            }

        } catch (\Exception $exception) {
            $this->log('error', $exception->getMessage());
        }
    }

    private function createContact(\stdClass $lead): \stdClass
    {
        try {
            $contact_name = $this->hubspotService->spliteContactName($lead->name);
            $lead_data = [
                'firstname' => $contact_name['first_name'],
                'lastname' => $contact_name['last_name'],
                'email' => strtolower(trim($lead->email)),
                'elegido_foto' => $lead->media_source,
                'phone' => preg_replace('/[\(\-\) \s]/', '', $lead->phone)
            ];

            $contact = $this->hubspotService->createNewContact($lead_data);

        } catch (\Exception $exception) {
            $this->log('error','['.$lead->email.'] '.$exception->getMessage());
        }

        return $contact;
    }

    private function addContactToList(\stdClass $contact, \stdClass $lead): bool
    {
        try {
            $add_list_data = [
                'list_id' => config('hubspot.hubspot_list'),
                'contacts' => ['vids' => [$contact->id]],
            ];

            $response = $this->hubspotService->addContactToList($add_list_data);

            $this->log('info','['.$lead->email.'] Donor added on Hubspot List');

        } catch (\Exception $exception) {
            $this->log('error','['.$lead->email.'] '.$exception->getMessage());
        }

        return $response;
    }

    private function updateDonor(\stdClass $lead):Donor
    {
        try {
            $donor = Donor::where('id', $lead->id)->first();
            $donor->hubspot_lead = 'sent';
            $donor->updated_at = date('Y-m-d H:i:s');
            $donor->save();

        } catch (\Exception $exception) {
            $this->log('error','['.$lead->email.'] '.$exception->getMessage());
        }

        $this->log('info','['.$lead->email.'] Donor updated on Chosen base');

        return $donor;
    }

    private function log(string $type, string $text): void
    {
        $log_text = '['.Carbon::now().'] '.$text;

        switch ($type) {
            case 'info':
                $this->info( $log_text);
                break;

            case 'warn':
                $this->warn( $log_text);
                break;

            case 'error':
                $this->error( $log_text);
                break;

            default:
                $this->line( $log_text);
                break;
        }
    }
}
