<?php

namespace App\Models;

use App\Exceptions\Donation\DonationNotFoundException;
use App\Types\DonationTypes;
use App\Types\DonorMediaTypes;
use App\Types\DonorTypes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Donation extends Model
{
    use HasFactory;

    protected $table = 'donations';

    protected $fillable = [
        'donor_id',
        'event_id',
        'simma_sync_details',
        'simma_sync_status',
        'simma_sync_date',
        'updated_at',
        'payments_donation_id', 'tracking_code', 'simma_payment_id', 'child_quantity', 'child_amount', 'amount',
        'revelation_type', 'revelation_amount', 'donation_status', 'extra_info', 'payment_date', 'donor_media_id',
        'payment_trans_id', 'status'
    ];

    protected $casts = [];

    public function donor()
    {
        return $this->belongsTo(Donor::class);
    }

    public function photo()
    {
        return $this->belongsTo(DonorMedia::class, 'donor_media_id', 'id');
    }

    public static function getDonationRefusedByDonorId(Donor $donor)
    {
        return Donation::select('donations.id', DB::raw('donors.id as donor'),
            'donations.child_quantity', 'donations.amount', 'donations.revelation_type', 'donations.revelation_amount',
            DB::raw('sum(donations.amount) as total_price'))
            ->leftjoin('donors', 'donors.id', 'donations.donor_id')
            ->leftjoin('donation_children', 'donation_children.donation_id', 'donations.id')
            ->where('donations.donor_id', $donor->id)
            ->where('donations.donation_status', 'refused')
            ->groupBy('donations.id')
            ->orderBy('donations.created_at', 'desc')
            ->first();
    }

    public static function getDonationsByDonorId(int $donor_id)
    {
        try {
            return Donation::select('donations.id', 'donations.child_quantity', 'donations.donor_id')
                ->join('donors', 'donors.id', 'donations.donor_id')
                ->join('donor_medias', 'donor_medias.id', 'donations.donor_media_id')
                ->where('donations.donor_id', $donor_id)
                ->where('donations.status', DonationTypes::STATUS_VALID)
                ->where('donors.simma_sync_status', DonorTypes::SIMMA_SYNCED)
                ->where('donor_medias.validation_status', DonorMediaTypes::APPROVED)
                ->get();
        } catch (\Exception $e) {
            throw new DonationNotFoundException($e);
        }
    }
}
