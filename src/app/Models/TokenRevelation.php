<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;
use Illuminate\Support\Str;

class TokenRevelation extends Model
{
    use HasFactory;

    protected $table = 'token_revelations';

    protected $fillable = [
        'token', 'donor_id', 'child_id', 'active', 'expired_at'
    ];

    protected $casts = [
        'active' => 'boolean',
        'expired_at' => 'datetime'
    ];

    public static function createToken($donor_id, $child_id): Model
    {
        $token = Str::uuid();

        return self::create([
            'token' => $token,
            'donor_id' => $donor_id,
            'child_id' => $child_id,
            'active' => true,
            'expired_at' => Carbon::now()->format('Y-m-d H:i:s')
        ]);
    }
}
