<?php

namespace App\Console\Commands\SimmaIntegration;

use App\Models\Donor;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Encryption\Encrypter;
use Illuminate\Support\Facades\Http;

use App\Models\Donation;
use App\Types\SimmaIntegrationTypes;
use App\Services\Logging\Facades\Logging;
use Exception;

abstract class SimmaIntegration extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'simma:payment-donor';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * HTTP Client to send request
     *
     * @var \Illuminate\Support\Facades\Http
     */
    protected $httpClient;

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();

        $this->httpClient = Http::baseUrl(config('simma.api_base_url'))
            ->withHeaders(['Content-Type' => 'application/json']);
    }

    /**
     * Execute the console command.
     *
     * @return void
     */
    public function handle()
    {
        $this->message('Starting SIMMA integration');

        $this->getDonationPending();

        if (isset($this->donation['donor']['id'])) {
            $this->message('Donor ID: ' . $this->donation['donor']['id']);
            $this->message('Donor Name: ' . $this->donation['donor']['name']);

            $this->sendDataSimma($this->donation);

            $this->message('Finished SIMMA integration');
        } else {
            $this->message('Finished No Donor');
        }
    }

    /**
     * @return void
     */
    private function getDonationPending()
    {
        $donation = Donation::with('donor')
            ->where('simma_sync_status', SimmaIntegrationTypes::STATUS_PENDING)
            ->orderBy('id', 'desc')
            ->first();

        $this->donation = $donation ? $donation : null;
    }

    /**
     * @return void
     */
    protected function addSimmaSyncStatus()
    {
        $this->donation->simma_sync_status = SimmaIntegrationTypes::STATUS_SYNCED;
        $this->donation->save();

        if (isset($this->donation->donor->id)) {
            Donor::where('id', $this->donation->donor->id)
                ->where(function ($query){
                    $query->where('simma_sync_status', '<>', SimmaIntegrationTypes::STATUS_SYNCED);
                    $query->orWhere('simma_sync_status', null);
                })
                ->update(['simma_sync_status' => SimmaIntegrationTypes::STATUS_SYNCED]);
        }
    }

    /**
     * @param string $simma_donor_id
     * @return void
     */
    protected function addSimmaDonorID(string $simma_donor_id)
    {
        $this->donation->donor()->update(['simma_donor_id' => $simma_donor_id]);
    }

    /**
     * @param \Illuminate\Database\Eloquent\Model $donation
     */
    protected abstract function sendDataSimma($donation);

    /**
     * @return void
     */
    protected function setDonationSyncStatusError($message)
    {
        try {
            $this->donation->simma_sync_status = SimmaIntegrationTypes::STATUS_SYNCED_ERROR;
            $this->donation->save();

            Logging::info(['message' => json_encode($message), 'error_code' => 500]);

            throw new Exception(json_encode($message));
        } catch (Exception $e) {
            throw new Exception('Error in change status to [synced_error]: ' . $e->getMessage(), $e->getCode());
        }
    }

    /**
     * @param string $encrypted
     * @return array
     */
    protected function getCardEncrypted(string $encrypted): array
    {
        try {
            $encrypter = new Encrypter(config('simma_integration.encrypt_key'), config('app.cipher'));
            $card_encrypted = $encrypter->decrypt($encrypted);
            parse_str($card_encrypted, $card_decrypted);

            $card_decrypted['expiration'] = sprintf('20%s-%s-01', $card_decrypted['exp_year'], $card_decrypted['exp_month']);

            return $card_decrypted;
        } catch (Exception $e) {
            throw new Exception('Something wrong with encrypt_key: ' . $e->getMessage(), $e->getCode());
        }
    }

    /**
     * @param string $message
     * @param string $type "line" | "info"
     * @return void
     */
    protected function message(string $message, $type = 'line')
    {
        switch ($type) {
            case 'info':
                $this->info(Carbon::now() . ' ' . $message);
                break;
            default:
                $this->line(Carbon::now() . ' ' . $message);
                break;
        }
    }
}

