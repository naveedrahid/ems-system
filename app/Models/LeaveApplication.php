<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LeaveApplication extends Model
{
    use HasFactory;

    protected $guarded = ['id'];
    
    function leaveType(){
        return $this->belongsTo(LeaveType::class);
    }
}
