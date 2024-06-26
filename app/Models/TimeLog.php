<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class TimeLog extends Model
{
    use SoftDeletes;
    use HasFactory;

    protected $fillable = [
        'user_id', 'attendance_id', 'start_time', 'end_time', 'duration'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
