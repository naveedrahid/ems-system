<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LeaveApplication extends Model
{
    use HasFactory;

    protected $fillable = ['employee_id','user_id', 'leave_type_id', 'start_date', 'end_date', 'reason', 'leave_image','total_leave','status'];
    
    public static function getStatusOptions()
    {
        return [
            'Pending' => 'Pending',
            'Approved' => 'Approved',
            'Rejected' => 'Rejected',
        ];
    }
    
    function leaveType(){
        return $this->belongsTo(LeaveType::class);
    }

    function user(){
        return $this->belongsTo(User::class, 'user_id');
    }

    function employee(){
        return $this->belongsTo(Employee::class, 'user_id', 'user_id');
    }
}
