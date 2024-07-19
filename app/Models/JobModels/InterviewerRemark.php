<?php

namespace App\Models\JobModels;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class InterviewerRemark extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $guarded = ['id'];

    public static function candidateStatus() {
        return [
            'Pending',
            'Selected',
            'Rejected',
        ];
    }

    public function candidate(){
        return $this->belongsTo(Candidate::class);
    }
    
    public function job() {
        return $this->belongsTo(Job::class);
    }
    
    public function user() {
        return $this->belongsTo(User::class, 'interviewer_id');
    }

    public function scheduleInterview() {
        return $this->belongsTo(ScheduleInterview::class, 'schedule_interview_id');
    }

    public function jobOffer()  {
        return $this->belongsTo(JobOffer::class);
    }
}
