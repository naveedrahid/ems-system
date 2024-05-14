<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Employee extends Model
{
    use HasFactory;

    // protected $fillable = [
    //     'user_id', 'date_of_birth', 'joining_date', 'fater_name', 'city', 'address', 'phone_number', 'emergency_phone_number', 'emergency_person_name', 'employee_img', 'gender',
    // ];

    protected $guarded = [];

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

}
