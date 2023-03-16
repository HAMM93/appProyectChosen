<?php

namespace App\Models;

use App\Casts\CardEncryptCast;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SimmaIntegrationPaymentTransaction extends Model
{
    use HasFactory;

    protected $table = 'simma_integration_payment_transactions';

    protected $fillable = ['donor_id', 'donation_id', 'card'];

    protected $casts = ['card' => CardEncryptCast::class];
}
