<?php

declare(strict_types=1);

namespace App\Console\Commands;

use App\Exceptions\Children\ChildrenNotFoundException;
use App\Exceptions\Donor\DonorInvalidException;
use App\Exceptions\Donor\DonorNotFound;
use App\Exceptions\Package\PackageDonorInvalidException;
use App\Exceptions\Package\PackageDonorNotLinkedException;
use App\Exceptions\Package\PackageNotCreatedException;
use App\Helpers\PackageHelper;
use App\Models\Donation;
use App\Models\Donor;
use App\Models\DonorEvent;
use App\Models\DonorMedia;
use App\Models\Event;
use App\Types\DonationTypes;
use App\Types\DonorEventTypes;
use App\Types\DonorMediaTypes;
use App\Types\PackageTypes;
use Illuminate\Console\Command;
use Illuminate\Database\Eloquent\Collection;

class AddDonorIntoPackage extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'donor:add-into-package';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Add a donor to package, and if the package is full it creates a new package and add to it.
    command:add-donor-into-package';

    /**
     * Quantity of children in package
     * @var int
     */
    private int $package_child_quantity = 0;

    /**
     * Last event created
     * @var Event
     */
    private Event $last_package;

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return void
     * @throws PackageNotCreatedException
     * @throws PackageDonorNotLinkedException
     * @throws DonorNotFound
     * @throws ChildrenNotFoundException
     * @throws \Exception
     */
    public function handle()
    {
        $this->info('Looking for available donors to join a package...');

        $donors = Donor::getDonorsWithoutPackage();

        $this->info(str_repeat('=', 80));

        $quantity_donors = count($donors->toArray());

        if ($quantity_donors > 0) {
            $this->info($quantity_donors . ' found donor(s).');

            foreach ($donors as $donor) {
                $this->last_package = Event::getLastPackage() ?? $this->createPackage();

                $donations = Donation::getDonationsByDonorId($donor->donor_id);

                if (count($donations->toArray()) > 0) {
                    $this->package_child_quantity = Event::getCountOfChildrenInPackage($this->last_package);

                    $valid_donations = $this->getValidDonations($donations, $this->last_package);

                    foreach ($valid_donations->available_donations as $donation) {
                        if ($this->package_child_quantity < PackageTypes::MAX_COMPROMISES) {
                            $this->addDonorToPackage($donor, $this->last_package, $donation);
                        } else {
                            $this->last_package = $this->createPackage($this->last_package);
                            $this->addDonorToPackage($donor, $this->last_package, $donation);
                        }

                        $this->info(str_repeat('=', 80));
                    }
                }
            }
        } else {
            $this->error('No donors found.');
            $this->info(str_repeat('=', 80));
        }
    }

    /**
     * @param Donor $donor
     * @param Event $package
     * @param object $donation
     * @throws PackageDonorNotLinkedException
     */
    private function addDonorToPackage(Donor $donor, Event $package, object $donation): void
    {
        $donor_appointments = $this->getQuantityOfAvailableAppointmentsByDonation($donation->donation, $package);

        try {
            for ($i = 1; $i <= $donor_appointments; $i++) {
                DonorEvent::create([
                    'event_id' => $package->id,
                    'donor_id' => $donor->donor_id,
                    'donation_id' => $donation->donation->id
                ]);
            }

            $this->package_child_quantity = Event::getCountOfChildrenInPackage($package);

            $this->info('Donor: ' . $donor->donor_id . '| Donation: ' . $donation->donation->id .
                ' was linked to package: ' . $package->id . ' with ' . $donor_appointments . ' appointments.');

            $this->last_package = Event::getLastPackage();

            if ($this->package_child_quantity == PackageTypes::MAX_COMPROMISES) {
                $package->where('id', $package->id)
                    ->update([
                        'status_participante' => 0
                    ]);
            }
        } catch (\Exception $e) {
            throw new PackageDonorNotLinkedException($e);
        }
    }

    /**
     * @param Donation $donation
     * @param Event $last_package
     * @return int
     */
    private function getQuantityOfAvailableAppointmentsByDonation(Donation $donation, Event $last_package): int
    {
        $current_donor_appointments = DonorEvent::where('donation_id', $donation->id)
            ->where('donor_id', $donation->donor_id)
            ->where('status', DonorEventTypes::VALID)
            ->get()
            ->count();

        $quantity_donor_event_invalid = DonorEvent::where('donation_id', $donation->id)
            ->where('event_id', $last_package->id)
            ->where('donor_id', $donation->donor_id)
            ->where('status', DonorEventTypes::INVALID)
            ->get()
            ->count();

        if ($quantity_donor_event_invalid > 0) {
            $donation->child_quantity -= $quantity_donor_event_invalid;
        }

        $available_donor_appointments = $donation->child_quantity - $current_donor_appointments;

        if ($available_donor_appointments == 0) {
            if (($donation->child_quantity += $quantity_donor_event_invalid) - $current_donor_appointments == 0) {
                $donation->where('id', $donation->id)->update(['status' => 'invalid']);

                $this->error('Donor: ' . $donation->donor_id . '| Donation: ' . $donation->id . ' cannot have more appointments.');
            } else {
                $this->error('Donor: ' . $donation->donor_id . '| Donation: ' . $donation->id . ' cant back to package '. $last_package->id);
            }

            return $available_donor_appointments;
        }

        $available_package_appointments = PackageTypes::MAX_COMPROMISES - $this->package_child_quantity;

        if ($available_package_appointments > 0 && $available_package_appointments < $available_donor_appointments) {
            return $available_package_appointments;
        } else {
            return $available_donor_appointments;
        }
    }

    /**
     * @param Collection $donations
     * @param Event $last_package
     * @return object
     */
    private function getValidDonations(Collection $donations, Event $last_package): object
    {
        foreach ($donations as $donation) {
            $donor_appointments = $this->getQuantityOfAvailableAppointmentsByDonation($donation, $last_package);

            if ($donor_appointments > 0) {
                $available_donations[] = (object)[
                    'donation' => $donation,
                ];
            }
        }

        return (object)[
            'available_donations' => $available_donations ?? []
        ];
    }

    /**
     * @param Event|null $last_package
     * @return Event
     * @throws PackageNotCreatedException
     */
    private function createPackage(Event $last_package = null): Event
    {
        $this->info(str_repeat('=', 80));
        $this->info('Creating new package...');

        if (!is_null($last_package)) {
            $title = PackageHelper::makeTitle($last_package);
        } else {
            $title = DonorEventTypes::FIRST_EVENT_TITLE;
        }

        $new_package = Event::create([
            'title' => $title,
            'max_participants' => PackageTypes::MAX_COMPROMISES,
            'status_participante' => 1
        ]);

        $this->info('Package: ' . $new_package->id . ' created.');
        $this->info(str_repeat('=', 80));

        return $new_package;
    }
}
