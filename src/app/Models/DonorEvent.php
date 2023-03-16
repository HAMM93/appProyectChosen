<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class DonorEvent extends Model
{
    use HasFactory;

    protected $table = 'donor_event';

    protected $fillable = [
        'event_id', 'donor_id', 'donation_id', 'donation_child_id',  'status'
    ];

    protected $casts = [];
}
