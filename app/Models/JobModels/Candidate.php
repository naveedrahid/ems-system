<?php

namespace App\Models\JobModels;

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
            'Interview Scheduled',
        ];
    }

    public static function cities()
    {
        return [
            'Karachi',
            'Lahore',
            'Faisalabad',
            'Rawalpindi',
            'Multan',
            'Hyderabad',
            'Gujranwala',
            'Peshawar',
            'Quetta',
            'Islamabad',
            'Sargodha',
            'Sialkot',
            'Bahawalpur',
            'Sukkur',
            'Jhang',
            'Sheikhupura',
            'Larkana',
            'Gujrat',
            'Mardan',
            'Kasur',
            'Rahim Yar Khan',
            'Sahiwal',
            'Okara',
            'Wah',
            'Dera Ghazi Khan',
            'Mirpur Khas',
            'Nawabshah',
            'Mingora',
            'Chiniot',
            'Kamoke',
            'Mandi Burewala',
            'Jhelum',
            'Sadiqabad',
            'Jacobabad',
            'Shikarpur',
            'Khanewal',
            'Hafizabad',
            'Kohat',
            'Muzaffargarh',
            'Khanpur',
            'Gojra',
            'Bahawalnagar',
            'Muridke',
            'Pak Pattan',
            'Abottabad',
            'Tando Adam',
            'Jaranwala',
            'Khairpur',
            'Chishtian Mandi',
            'Daska',
            'Dadu',
            'Mandi Bahauddin',
            'Ahmadpur East',
            'Kamalia',
            'Khuzdar',
            'Vihari',
            'Dera Ismail Khan',
            'Wazirabad',
            'Nowshera',
        ];
    }

    public function job()
    {
        return $this->belongsTo(Job::class);
    }

    public function scheduleInterviews()
    {
        return $this->hasMany(scheduleInterview::class);
    }

    public function jobOffer(){
        return $this->hasOne(JobOffer::class);
    }
}
