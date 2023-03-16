<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ManagementCode extends Model
{
    use HasFactory;

    protected $table = 'management_codes';

    protected $fillable = ['code_str'];

    protected $casts = [];
}
