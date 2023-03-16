<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Exceptions\Children\ChildrenNotFoundException;
use App\Exceptions\Revelation\RevelationNotFoundException;
use App\Models\DonationChildren;
use App\Models\DonorEvent;
use App\Models\Event;
use App\Types\DonorEventTypes;
use App\Types\PackageTypes;
use App\Types\RevelationTypes;
use Carbon\Carbon;
use Dompdf\Exception;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;

class RevelationRepository extends AbstractRepository
{
    public function __construct()
    {
        $this->per_page = PackageTypes::DEFAULT_PER_PAGE;
    }

    /**
     * @param int $per_page
     * @param int $page
     * @return Collection
     * @throws RevelationNotFoundException
     */
    public function getRevelationsAll(int $per_page, int $page): Collection
    {
        try {
            $results = DonorEvent::select('events.id as event_id', 'events.title', 'events.event_date',
                DB::raw("'0' as physical_revelation, '0' as digital_revelation, '0' as physical_revelation_total,
                count(donor_event.id) as quantity_donors"))
                ->join('donations', 'donations.id', '=', 'donor_event.donation_id')
                ->join('donors', 'donors.id', '=', 'donor_event.donor_id')
                ->join('events', 'events.id', '=', 'donor_event.event_id')
                ->where('events.event_date', '<=', Carbon::now())
                ->groupBy('events.id', 'events.title', 'events.event_date');
        } catch (\Exception $e) {
            throw new RevelationNotFoundException($e);
        }


        return $this->setPaginate($results, $per_page, $page);
    }

    /**
     * @param string $tracking_code
     * @param int $per_page
     * @param int $page
     * @return Collection
     * @throws RevelationNotFoundException
     */
    public function getRevelationByTrackingCode(string $tracking_code, int $per_page, int $page): Collection
    {
        try {
            $result = DonorEvent::select('events.id as event_id', 'events.title', 'events.event_date',
                DB::raw("'0' as physical_revelation, '0' as digital_revelation, '0' as physical_revelation_total,
                count(donor_event.id) as quantity_donors"))
                ->leftjoin('donations', 'donations.id', '=', 'donor_event.donation_id')
                ->leftjoin('donors', 'donors.id', '=', 'donor_event.donor_id')
                ->leftjoin('events', 'events.id', '=', 'donor_event.event_id')
                ->where('donations.tracking_code', $tracking_code)
                ->where('events.event_date', '<=', Carbon::now())
                ->groupBy('events.id', 'events.title', 'events.event_date');
        } catch (\Exception $e) {
            throw new RevelationNotFoundException($e);
        }

        return $this->setPaginate($result, $per_page, $page);
    }

    /**
     * @param string $title
     * @param int $per_page
     * @param int $page
     * @return Collection
     * @throws RevelationNotFoundException
     */
    public function getRevelationByCHCode(string $title, int $per_page, int $page): Collection
    {
        try {
            $result = DonorEvent::select('events.id as event_id', 'events.title', 'events.event_date',
                DB::raw("'0' as physical_revelation, '0' as digital_revelation, '0' as physical_revelation_total,
                 count(donor_event.id) as quantity_donors"))
                ->leftjoin('donations', 'donations.id', '=', 'donor_event.donation_id')
                ->leftjoin('donors', 'donors.id', '=', 'donor_event.donor_id')
                ->leftjoin('events', 'events.id', '=', 'donor_event.event_id')
                ->where('events.title', strtoupper($title))
                ->where('events.event_date', '<=', Carbon::now())
                ->groupBy('events.id', 'events.title', 'events.event_date')
                ->orderBy('events.id', 'desc');
        } catch (\Exception $e) {
            throw new RevelationNotFoundException($e);
        }

        return $this->setPaginate($result, $per_page, $page);
    }

    /**
     * @param string $name
     * @param int $per_page
     * @param int $page
     * @return Collection
     * @throws RevelationNotFoundException
     */
    public function getRevelationByName(string $name, int $per_page, int $page): Collection
    {
        try {
            $result = DonorEvent::select('events.id as event_id', 'events.title', 'events.event_date',
                DB::raw("'0' as physical_revelation, '0' as digital_revelation, '0' as physical_revelation_total,
                count(donor_event.id) as quantity_donors"))
                ->leftjoin('donations', 'donations.id', '=', 'donor_event.donation_id')
                ->leftjoin('donors', 'donors.id', '=', 'donor_event.donor_id')
                ->leftjoin('events', 'events.id', '=', 'donor_event.event_id')
                ->where('donors.name', 'like', '%' . $name . '%')
                ->where('events.event_date', '<=', Carbon::now())
                ->groupBy('events.id', 'events.title', 'events.event_date')
                ->orderBy('events.id', 'desc');
        } catch (\Exception $e) {
            throw new RevelationNotFoundException($e);
        }

        return $this->setPaginate($result, $per_page, $page);
    }

    /**
     * @param string $email
     * @param int $per_page
     * @param int $page
     * @return Collection
     * @throws RevelationNotFoundException
     */
    public function getRevelationByEmail(string $email, int $per_page, int $page): Collection
    {
        try {
            $result = DonorEvent::select('events.id as event_id', 'events.title', 'events.event_date',
                DB::raw("'0' as physical_revelation, '0' as digital_revelation, '0' as physical_revelation_total,
                count(donor_event.id) as quantity_donors"))
                ->leftjoin('donations', 'donations.id', '=', 'donor_event.donation_id')
                ->leftjoin('donors', 'donors.id', '=', 'donor_event.donor_id')
                ->leftjoin('events', 'events.id', '=', 'donor_event.event_id')
                ->where('donors.email', $email)
                ->where('events.event_date', '<=', Carbon::now())
                ->groupBy('events.id', 'events.title', 'events.event_date')
                ->orderBy('events.id', 'desc');
        } catch (\Exception $e) {
            throw new RevelationNotFoundException($e);
        }

        return $this->setPaginate($result, $per_page, $page);
    }

    /**
     * @param string $partnerID
     * @param int $per_page
     * @param int $page
     * @return Collection
     * @throws RevelationNotFoundException
     */
    public function getRevelationByPartnerID(string $partnerID, int $per_page, int $page): Collection
    {
        try {
            $result = DonorEvent::select('events.id as event_id', 'events.title', 'events.event_date',
                DB::raw("'0' as physical_revelation, '0' as digital_revelation, '0' as physical_revelation_total,
                count(donor_event.id) as quantity_donors"))
                ->leftjoin('donations', 'donations.id', '=', 'donor_event.donation_id')
                ->leftjoin('donors', 'donors.id', '=', 'donor_event.donor_id')
                ->leftjoin('events', 'events.id', '=', 'donor_event.event_id')
                ->where('donors.simma_donor_id', $partnerID)
                ->where('events.event_date', '<=', Carbon::now())
                ->groupBy('events.id', 'events.title', 'events.event_date')
                ->orderBy('events.id', 'desc');
        } catch (\Exception $e) {
            throw new RevelationNotFoundException($e);
        }

        return $this->setPaginate($result, $per_page, $page);
    }

    /**
     * @param Event $event
     * @param int $per_page
     * @param int $page
     * @return object
     * @throws RevelationNotFoundException
     */
    public function getContentFromRevelation(Event $event, int $per_page, int $page): object
    {
        try {
            $revelations = DonorEvent::select(DB::raw('donor_event.id as donor_event_id, donations.child_quantity as
                total_child_quantity, count(donor_event.donor_id = donors.id) as child_quantity_in_package'),
                'donors.id as donor_id', 'donors.simma_donor_id', 'donors.name', 'donations.revelation_type', 'donations.donation_status',
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
                ->groupBy('donors.id');
        } catch (Exception $e) {
            throw new RevelationNotFoundException($e);
        }

        $donors = $this->setPaginate($revelations, $per_page, $page);

        return (object)[
            'package_info' => [
                'id' => $event->id,
                'title' => $event->title,
                'date' => $event->event_date,
                'package_donors' => $donors
            ],
        ];
    }

    /**
     * @param int $donation_id
     * @param string $child_id
     * @param string $child_name
     * @param string $child_city
     * @param string $child_photo
     * @param string $child_video
     * @param string $letter_photo
     * @return mixed
     * @throws RevelationNotFoundException
     */
    public function linkChildToDonation(
        int $donation_id,
        string $child_id,
        string $child_name,
        string $child_city,
        string $child_photo,
        string $child_video,
        string $letter_photo
    )
    {
        try {
            $result = DonationChildren::create([
                'donation_id' => $donation_id,
                'simma_child_id' => $child_id,
                'child_name' => $child_name,
                'child_age' => 10,
                'child_city' => $child_city,
                'child_photo' => $child_photo,
                'child_video' => $child_video,
                'letter_photo' => $letter_photo
            ]);
        } catch (\Exception $e) {
            throw new RevelationNotFoundException($e);
        }

        return $result;
    }

    /**
     * @param DonationChildren $child
     * @return Collection
     * @throws ChildrenNotFoundException
     */
    public function getChildrenInfo(DonationChildren $child): Collection
    {
        try {
            return $child->select('donation_children.child_name', 'donation_children.child_city', 'donations.tracking_code',
                DB::raw("CASE
                WHEN donation_children.revelation_group_id IS NULL THEN '" . RevelationTypes::DIGITAL . "'
                ELSE '" . RevelationTypes::PHYSICAL . "' END as revelation_type,
                '" . RevelationTypes::NOT_SENT . "' as digital_sending"),
                'donation_children.child_photo', 'donation_children.child_video', 'donation_children.letter_photo')
                ->join('donations', 'donations.id', '=', 'donation_children.donation_id')
                ->where('donation_children.id', $child->id)
                ->groupBy('donation_children.child_name', 'donation_children.child_city', 'donations.tracking_code',
                    'donation_children.child_photo', 'donation_children.child_video', 'donation_children.letter_photo',
                    'donation_children.revelation_group_id')
                ->get();
        } catch (\Exception $e) {
            throw new ChildrenNotFoundException($e);
        }
    }

    /**
     * @param int $donor_id
     * @param string $child_id
     * @return array
     * @throws RevelationNotFoundException
     */
    public function getAllRevelationOccurred(int $donor_id, string $child_id): array
    {
        try {
            $revelations = DonationChildren::selectRaw('donors.simma_donor_id as donor_id, donation_children.simma_child_id as child_id
           , donation_children.created_at as revelation_date')
                ->join('donations', 'donations.id', 'donation_children.donation_id')
                ->join('donors', 'donors.id', 'donations.donor_id')
                ->join('donor_event', function ($join) {
                    $join->on('donor_event.donor_id', 'donors.id');
                    $join->on('donor_event.donation_id', 'donations.id');
                    $join->on('donor_event.donation_child_id', 'donation_children.id');
                })
                ->when($donor_id !== 0, function ($query) use ($donor_id) {
                    return $query->where('donors.simma_donor_id', $donor_id);
                })
                ->when($child_id != 0, function ($query) use ($child_id) {
                    return $query->where('donation_children.simma_child_id', $child_id);
                })
                ->whereRaw('
                    donors.simma_donor_id is not null
                    and donation_children.simma_child_id is not null
                    and donation_children.child_name is not null
                    and donation_children.child_age is not null
                    and donation_children.child_city is not null
                    and donation_children.child_photo is not null
                    and donation_children.child_video is not null
                    and donation_children.letter_photo is not null
                ')
                ->groupBy('donation_children.created_at', 'donation_children.simma_child_id', 'donors.simma_donor_id')
                ->orderBy('donation_children.created_at', 'desc')
                ->get()
                ->toArray();
        } catch (\Exception $e) {
            throw new RevelationNotFoundException($e);
        }

        return $revelations;
    }
}
