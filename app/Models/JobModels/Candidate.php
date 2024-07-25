<?php

namespace App\Models\JobModels;

use App\Models\City;
use App\Models\Country;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Candidate extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $guarded = ['id'];

    public static function candidate_application_status()
    {
        return [
            'Pending',
            'Selected',
            'Rejected',
        ];
    }

    public function job()
    {
        return $this->belongsTo(Job::class);
    }

    public function city()
    {
        return $this->belongsTo(City::class);
    }

    public function country()
    {
        return $this->belongsTo(Country::class);
    }

    public function scheduleInterviews()
    {
        return $this->hasMany(scheduleInterview::class);
    }

    public function jobOffer(){
        return $this->hasOne(JobOffer::class);
    }
}
