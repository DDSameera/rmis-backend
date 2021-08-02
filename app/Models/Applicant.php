<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Applicant extends Model
{
    use HasFactory;

    protected $primaryKey = 'user_id';

    public $incrementing = false;

    /* * Default Time Stamp Disable */
    public $timestamps = false;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id',
        'onboarding_percentage',
        'count_applications',
        'count_accepted_applications',
    ];

    /**
     * User Reationshop
     *
     * @return HasOne
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
