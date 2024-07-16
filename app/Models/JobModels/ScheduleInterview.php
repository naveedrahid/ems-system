<?php

namespace App\Models\JobModels;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ScheduleInterview extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $guarded = ['id'];

    public function candidate(){
        return $this->belongsTo(Candidate::class);
    }

    public static function interviewTypes()
    {
        return ['initial', 'technical', 'final'];
    }

    public function job() {
        return $this->belongsTo(Job::class);
    }
    
    public function user() {
        return $this->belongsTo(User::class, 'interviewer_id');
    }

    public function interviewerRemarks(){
        return $this->hasMany(InterviewerRemark::class, 'schedule_interview_id');
    }
    public function jobOffer(){
        return $this->hasOne(JobOffer::class);
    }
}
