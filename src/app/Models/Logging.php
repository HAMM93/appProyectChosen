<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Logging extends Model
{
    use HasFactory;

    protected $table = 'logging';

    protected $fillable = [
        'level',
        'uuid',
        'message',
        'error_code',
        'trace',
        'additional'
    ];

    protected $casts = [];
}
