<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class FinanceTransaction extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'finance_transactions';

    protected $fillable = [
        'type', 'payment_id'
    ];

    protected $casts = [];
}
