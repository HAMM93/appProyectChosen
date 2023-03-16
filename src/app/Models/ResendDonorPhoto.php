<?php

namespace App\Models;

use App\Exceptions\DonorMedia\DonorMediaTokenNotCreatedException;
use App\Mail\ResendDonorPhotoEmail;
use App\Services\Logging\Facades\Logging;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class ResendDonorPhoto extends Model
{
    use HasFactory;

    protected $table = 'resend_donor_photo';

    protected $fillable = [
        'token', 'expired_at', 'status', 'donor_id'
    ];

    protected $casts = [];

    public function donor(): BelongsTo
    {
        return $this->belongsTo(Donor::class);
    }
}
