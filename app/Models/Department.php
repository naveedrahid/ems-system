<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Department extends Model
{
    use HasFactory;

    protected $fillable = ['department_name','status',];

    public function employees()
    {
        return $this->hasMany(Employee::class);
    }
    public function designations(){
        return $this->hasMany(Designation::class);
    }
}
