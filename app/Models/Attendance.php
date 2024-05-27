<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Attendance extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id', 'attendance_date', 'check_in', 'check_in_status' , 'check_out', 'check_out_status' , 'status', 'total_overtime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
