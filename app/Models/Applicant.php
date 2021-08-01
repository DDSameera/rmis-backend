<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Applicant extends Model
{
    use HasFactory;

    /*Primary key*/
    protected $primaryKey = 'user_id';

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


    public function user()
    {
        return $this->hasOne(Applicant::class);
    }
}
