<?php

namespace App\Models\JobModels;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class JobOffer extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $guarded = ['id'];

    public function job() {
        return $this->belongsTo(Job::class);
    }

    public function candidate() {
        return $this->belongsTo(Candidate::class);
    }

    public function scheduleInterview() {
        return $this->belongsTo(ScheduleInterview::class);
    }
}
