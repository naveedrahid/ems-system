<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class DocumentUser extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = ['user_id', 'department_id', 'nic_front', 'nic_back','resume', 'payslip', 'experience_letter','bill'];

    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }
    public function user(){
        return $this->belongsTo(User::class, 'user_id');
    }
}
