<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Sessions extends Model
{
    use HasFactory;

    protected $table = 'sessions';

    protected $fillable = [
        'user_id',
        'disabled_by_session_id',
        'auth_secure_token',
        'status',
        'change_by',
        'expired_at'
    ];

    protected $casts = [];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
