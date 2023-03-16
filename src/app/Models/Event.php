<?php

namespace App\Models;

use App\Exceptions\Children\ChildrenNotFoundException;
use App\Exceptions\Package\PackageNotFoundException;
use App\Exceptions\Package\PackageNotUpdatedDateException;
use App\Types\DonationTypes;
use App\Types\DonorEventTypes;
use App\Types\DonorMediaTypes;
use App\Types\DonorTypes;
use App\Types\EventTypes;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Event extends Model
{
    use HasFactory;

    protected $table = 'events';

    protected $fillable = [
        'event_date', 'title', 'observation', 'extra_info', 'max_participants', 'status', 'status_participante'
    ];

    protected $casts = [];

    public static function getLastPackage()
    {
        //TODO:: (Joel 03/05) Adicionar condição por status
        return Event::where('status_participante', EventTypes::OPEN)->first();
    }

    public static function updateDateEvent(Event $event, Carbon $date): void
    {
        try {
            $event->event_date = $date;
            $event->save();
        } catch (\Exception $e) {
            throw new PackageNotUpdatedDateException($e);
        }
    }

    /**
     * @param Event $event
     * @return int
     * @throws ChildrenNotFoundException
     */
    public static function getCountOfChildrenInPackage(Event $event): int
    {
        try {
            $result = Donation::selectRaw('count(donations.child_quantity) as child_quantity')
                ->leftjoin('donor_event', 'donations.id', 'donor_event.donation_id')
                ->leftjoin('donors', 'donors.id', 'donor_event.donor_id')
                ->where('donor_event.event_id', '=', $event->id)
                ->where('donors.simma_sync_status', DonorTypes::SIMMA_SYNCED)
                ->where('donor_event.status', DonorEventTypes::VALID)
                ->get()
                ->toArray();
        } catch (\Exception $e) {
            throw new ChildrenNotFoundException($e);
        }

        return $result[0]['child_quantity'] ?? 0;
    }
}
