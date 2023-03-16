<?php

declare(strict_types=1);

namespace App\Repositories;


use App\Exceptions\Package\PackageNotFoundException;
use App\Models\Donor;
use App\Models\DonorEvent;
use App\Models\Event;
use App\Types\DonorEventTypes;
use App\Types\PackageTypes;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;

class PackageRepository extends AbstractRepository
{
    public function __construct()
    {
        $this->per_page = PackageTypes::DEFAULT_PER_PAGE;
    }

    /**
     * @param string $ch_code
     * @param string $status
     * @param int $per_page
     * @param int $page
     * @return Collection
     * @throws PackageNotFoundException
     */
    public function getPackagesByCHCode(string $ch_code, string $status, int $per_page, int $page): Collection
    {
        try {
            $date = Carbon::now()->format('Y-m-d H:i:s');

            $result = DonorEvent::select('events.title', 'events.event_date',
                DB::raw("events.id as package_id, CASE
           WHEN events.event_date IS NULL THEN '" . PackageTypes::PENDING . "'
           WHEN events.event_date < NOW() THEN '" . PackageTypes::ACCOMPLISHED . "'
           WHEN events.event_date > NOW() THEN '" . PackageTypes::SCHEDULED . "'
           END as status,
           count(donor_event.id) as child_quantity"))
                ->when($status === PackageTypes::PENDING, function ($query) {
                    return $query->where('events.event_date', null);
                })
                ->when($status === PackageTypes::SCHEDULED, function ($query) use ($date) {
                    return $query->where('events.event_date', '>', $date);
                })
                ->when($status === PackageTypes::ACCOMPLISHED, function ($query) use ($date) {
                    return $query->where('events.event_date', '<', $date);
                })
                ->leftjoin('donations', function ($join) {
                    $join->on('donations.id', '=', 'donor_event.donation_id');
                })
                ->leftjoin('donors', function ($join) {
                    $join->on('donors.id', '=', 'donor_event.donor_id');
                })
                ->leftjoin('events', function ($join) {
                    $join->on('events.id', '=', 'donor_event.event_id');
                })
                ->where('title', $ch_code)
                ->where('donor_event.status', 'valid')
                ->groupBy('events.title', 'events.event_date', 'events.id')
                ->orderBy('events.id', 'desc');
        } catch (\Exception $e) {
            throw new PackageNotFoundException($e);
        }

        return $this->setPaginate($result, $per_page, $page);
    }

    /**
     * @param int $partner_id
     * @param string $status
     * @param int $per_page
     * @param int $page
     * @return Collection
     * @throws PackageNotFoundException
     */
    public function getPackagesByPartnerID(int $partner_id, string $status, int $per_page, int $page): Collection
    {
        try {
            $date = Carbon::now()->format('Y-m-d H:m:s');

            $result = DonorEvent::select('events.title', 'events.event_date',
                DB::raw("events.id as package_id, CASE
           WHEN events.event_date IS NULL THEN '" . PackageTypes::PENDING . "'
           WHEN events.event_date < NOW() THEN '" . PackageTypes::ACCOMPLISHED . "'
           WHEN events.event_date > NOW() THEN '" . PackageTypes::SCHEDULED . "'
           END as status,
           count(donor_event.id) as child_quantity"))
                ->when($status === PackageTypes::PENDING, function ($query) {
                    return $query->where('events.event_date', null);
                })
                ->when($status === PackageTypes::SCHEDULED, function ($query) use ($date) {
                    return $query->where('events.event_date', '>', $date);
                })
                ->when($status === PackageTypes::ACCOMPLISHED, function ($query) use ($date) {
                    return $query->where('events.event_date', '<', $date);
                })
                ->leftjoin('donations', function ($join) {
                    $join->on('donations.id', '=', 'donor_event.donation_id');
                })
                ->leftjoin('donors', function ($join) {
                    $join->on('donors.id', '=', 'donor_event.donor_id');
                })
                ->leftjoin('events', function ($join) {
                    $join->on('events.id', '=', 'donor_event.event_id');
                })
                ->where('simma_donor_id', $partner_id)
                ->where('donor_event.status', 'valid')
                ->groupBy('events.title', 'events.event_date', 'events.id')
                ->orderBy('events.id', 'desc');
        } catch (\Exception $e) {
            throw new PackageNotFoundException($e);
        }

        return $this->setPaginate($result, $per_page, $page);

    }

    /**
     * @param string $email
     * @param string $status
     * @param int $per_page
     * @param int $page
     * @return Collection
     * @throws PackageNotFoundException
     */
    public function getPackagesByEmail(string $email, string $status, int $per_page, int $page): Collection
    {
        try {
            $date = Carbon::now()->format('Y-m-d H:m:s');

            $result = DonorEvent::select('events.title', 'events.event_date',
                DB::raw("events.id as package_id, CASE
           WHEN events.event_date IS NULL THEN '" . PackageTypes::PENDING . "'
           WHEN events.event_date < NOW() THEN '" . PackageTypes::ACCOMPLISHED . "'
           WHEN events.event_date > NOW() THEN '" . PackageTypes::SCHEDULED . "'
           END as status,
           count(donor_event.id) as child_quantity"))
                ->when($status === PackageTypes::PENDING, function ($query) {
                    return $query->where('events.event_date', null);
                })
                ->when($status === PackageTypes::SCHEDULED, function ($query) use ($date) {
                    return $query->where('events.event_date', '>', $date);
                })
                ->when($status === PackageTypes::ACCOMPLISHED, function ($query) use ($date) {
                    return $query->where('events.event_date', '<', $date);
                })
                ->leftjoin('donations', function ($join) {
                    $join->on('donations.id', '=', 'donor_event.donation_id');
                })
                ->leftjoin('donors', function ($join) {
                    $join->on('donors.id', '=', 'donor_event.donor_id');
                })
                ->leftjoin('events', function ($join) {
                    $join->on('events.id', '=', 'donor_event.event_id');
                })
                ->where('email', $email)
                ->where('donor_event.status', 'valid')
                ->groupBy('events.title', 'events.event_date', 'events.id')
                ->orderBy('events.id', 'desc');
        } catch (\Exception $e) {
            throw new PackageNotFoundException($e);
        }

        return $this->setPaginate($result, $per_page, $page);
    }

    /**
     * @param string $name
     * @param string $status
     * @param int $per_page
     * @param int $page
     * @return Collection
     * @throws PackageNotFoundException
     */
    public function getPackagesByName(string $name, string $status, int $per_page, int $page): Collection
    {
        try {
            $date = Carbon::now()->format('Y-m-d H:i:s');

            $result = DonorEvent::select('events.title', 'events.event_date',
                DB::raw("events.id as package_id, CASE
           WHEN events.event_date IS NULL THEN '" . PackageTypes::PENDING . "'
           WHEN events.event_date < NOW() THEN '" . PackageTypes::ACCOMPLISHED . "'
           WHEN events.event_date > NOW() THEN '" . PackageTypes::SCHEDULED . "'
           END as status,
           count(donor_event.id) as child_quantity"))
                ->when($status === PackageTypes::PENDING, function ($query) {
                    return $query->where('events.event_date', null);
                })
                ->when($status === PackageTypes::SCHEDULED, function ($query) use ($date) {
                    return $query->where('events.event_date', '>', $date);
                })
                ->when($status === PackageTypes::ACCOMPLISHED, function ($query) use ($date) {
                    return $query->where('events.event_date', '<', $date);
                })
                ->leftjoin('donations', function ($join) {
                    $join->on('donations.id', '=', 'donor_event.donation_id');
                })
                ->leftjoin('donors', function ($join) {
                    $join->on('donors.id', '=', 'donor_event.donor_id');
                })
                ->leftjoin('events', function ($join) {
                    $join->on('events.id', '=', 'donor_event.event_id');
                })
                ->where('name', 'like', '%' . $name . '%')
                ->where('donor_event.status', 'valid')
                ->groupBy('events.title', 'events.event_date', DB::raw('status'), 'events.id')
                ->orderBy('events.id', 'desc');
        } catch (\Exception $e) {
            throw new PackageNotFoundException($e);
        }

        return $this->setPaginate($result, $per_page, $page);
    }

    /**
     * @param string $status
     * @param int $per_page
     * @param int $page
     * @return Collection
     * @throws PackageNotFoundException
     */
    public function getPackagesByStatus(string $status, int $per_page, int $page): Collection
    {
        try {
            $date = Carbon::now()->format('Y-m-d H:i:s');

            $result = DonorEvent::select('events.title', 'events.event_date',
                DB::raw("events.id as package_id, CASE
           WHEN events.event_date IS NULL THEN '" . PackageTypes::PENDING . "'
           WHEN events.event_date < NOW() THEN '" . PackageTypes::ACCOMPLISHED . "'
           WHEN events.event_date > NOW() THEN '" . PackageTypes::SCHEDULED . "'
           END as status,
           count(donor_event.id) as child_quantity"))
                ->when($status === PackageTypes::PENDING, function ($query) {
                    return $query->where('events.event_date', null);
                })
                ->when($status === PackageTypes::SCHEDULED, function ($query) use ($date) {
                    return $query->where('events.event_date', '>', $date);
                })
                ->when($status === PackageTypes::ACCOMPLISHED, function ($query) use ($date) {
                    return $query->where('events.event_date', '<', $date);
                })
                ->leftjoin('donations', function ($join) {
                    $join->on('donations.id', '=', 'donor_event.donation_id');
                })
                ->leftjoin('donors', function ($join) {
                    $join->on('donors.id', '=', 'donor_event.donor_id');
                })
                ->leftjoin('events', function ($join) {
                    $join->on('events.id', '=', 'donor_event.event_id');
                })
                ->groupBy('events.title', 'events.event_date', 'events.id')
                ->where('donor_event.status', 'valid')
                ->orderBy('events.id', 'desc');
        } catch (\Exception $e) {
            throw new PackageNotFoundException($e);
        }

        return $this->setPaginate($result, $per_page, $page);
    }

    /**
     * @param Event $event
     * @param int $per_page
     * @param int $page
     * @return object
     * @throws PackageNotFoundException
     */
    public function getPackageContent(Event $event, int $per_page, int $page): object
    {
        try {
            $result = DonorEvent::select(DB::raw('donor_event.id as donor_event_id, donations.child_quantity as
                total_child_quantity, count(donor_event.donor_id = donors.id) as child_quantity_in_package'),
                'donors.id as donor_id', 'donors.simma_donor_id', 'donors.name', 'donations.donation_status',
                'donations.tracking_code', 'donor_medias.media_source')
                ->leftjoin('events', 'events.id', '=', 'donor_event.event_id')
                ->leftjoin('donors', 'donor_event.donor_id', '=', 'donors.id')
                ->leftJoin('donations', 'donations.id', '=', 'donor_event.donation_id')
                ->leftjoin('donor_medias', 'donors.id', '=', 'donor_medias.donor_id')
                ->whereRaw("events.id = $event->id
                    and donor_medias.id =
                    (select id from donor_medias
                    where donor_id = donors.id order by updated_at desc limit 1)
                    and donations.donation_status =
                    (select donation_status from donations
                    where donation_status = 'paid' order by payment_date desc limit 1)")
                ->where('donor_event.status', DonorEventTypes::VALID)
                ->groupBy('donor_event.id');
        } catch (\Exception $e) {
            throw new PackageNotFoundException($e);
        }

        $donors = $this->setPaginate($result, $per_page, $page);

        return (object)[
            'package_info' => [
                'id' => $event->id,
                'title' => $event->title,
                'date' => $event->event_date,
                'package_donors' => $donors
            ],
        ];
    }
}
