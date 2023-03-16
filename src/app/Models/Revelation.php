<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;

class Revelation extends Model
{
    use HasFactory;

    protected $table = 'revelations';

    protected $fillable = [
        'revelation_compiled_id',
        'type',
        'file',
        'compiled_status',
        'compiled_at',
        'extra_info',
        'created_at',
        'updated_at',
    ];

    protected $casts = [];

    public function revelationCompiled(): BelongsTo
    {
        return $this->belongsTo('App\Models\RevelationCompiled', 'revelation_compiled_id', 'id');
    }

    public function donationChildren_individual(): HasOne
    {
        return $this->hasOne('App\Models\DonationChildren', 'revelation_individual_id','id');
    }

    public function donationChildren_group(): HasMany
    {
        return $this->hasMany('App\Models\DonationChildren', 'revelation_group_id','id');
    }
}
