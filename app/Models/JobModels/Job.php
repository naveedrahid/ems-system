<?php

namespace App\Models\JobModels;

use App\Models\Department;
use App\Models\Designation;
use App\Models\Shift;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Job extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $guarded = ['id'];

    public function candidates(){
        return $this->hasMany(Candidate::class);
    }

    public function interviewerRemarks(){
        return $this->hasMany(Candidate::class);
    }

    public function jobOffer(){
        return $this->hasOne(JobOffer::class);
    }

    public function department(){
        return $this->belongsTo(Department::class);
    }

    public function designation(){
        return $this->belongsTo(Designation::class);
    }

    public function shift(){
        return $this->belongsTo(Shift::class);
    }
}
