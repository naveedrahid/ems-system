<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LeaveApplication extends Model
{
    use HasFactory;

    protected $fillable = ['employee_id', 'leave_type_id', 'start_date', 'end_date', 'reason', 'leave_image','total_leave','status'];
    
    const STATUS_PENDING = 'Pending';
    const STATUS_APPROVED = 'Approved';
    const STATUS_REJECTED = 'Rejected';

    public static function getStatusOptions()
    {
        return [
            self::STATUS_PENDING,
            self::STATUS_APPROVED,
            self::STATUS_REJECTED,
        ];
    }

    function leaveType(){
        return $this->belongsTo(LeaveType::class);
    }

    function user(){
        return $this->belongsTo(User::class, 'employee_id');
    }
}
