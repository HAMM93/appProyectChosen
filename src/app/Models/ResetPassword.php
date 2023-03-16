<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ResetPassword extends Model
{
    use HasFactory;

    protected $table = 'reset_passwords';

    protected $fillable = [
        'token_reset_password', 'expired_at', 'status'
    ];

    protected $casts = [];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
