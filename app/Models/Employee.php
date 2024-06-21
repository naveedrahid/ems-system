<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Employee extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id', 'department_id', 'date_of_birth','designation_id', 'employee_type_id', 'shift_id', 'joining_date', 'fater_name', 'city', 'address', 'phone_number', 'emergency_phone_number', 'emergency_person_name', 'employee_img','gender',
    ];

    // protected $guarded = ['id'];

    protected $dates = [
        'date_of_birth',
    ];
    
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function department()
    {
        return $this->belongsTo(Department::class);
    }

    public function designation()
    {
        return $this->belongsTo(Designation::class);
    }
    public function employeeType() {
        return $this->belongsTo(EmployeeType::class);
    }
    public function shift() {
        return $this->belongsTo(Shift::class);
    }
    public function LeaveApplication(){
        return $this->hasMany(LeaveApplication::class);
    }

    public function awards()
    {
        return $this->hasMany(Award::class, 'user_id', 'user_id');
    }

    public function bank()
    {
        return $this->hasMany(BankDetail::class);
    }
}