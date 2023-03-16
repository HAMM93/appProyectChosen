<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class StripeProducts extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'stripe_products';

    protected $fillable = [
        'name', 'price', 'product_object_id', 'recurring_interval',
        'price_object_id', 'currency', 'product_id'
    ];

    protected $casts = [];
}
