<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DonationChildren extends Model
{
    use HasFactory;

    protected $table = 'donation_children';

    protected $fillable = [
        'donation_id', 'simma_child_id', 'child_name', 'child_city', 'child_age', 'child_photo', 'letter_photo', 'child_video'
    ];

    protected $casts = [];

    public function donation()
    {
        return $this->belongsTo(Donation::class);
    }

    public static function getDonationChildrenByDonationId(int $donation_id): Collection
    {
        return DonationChildren::where('donation_id', $donation_id)->get();
    }
}
