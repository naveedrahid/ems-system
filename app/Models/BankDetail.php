<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class BankDetail extends Model
{
    use SoftDeletes;
    use HasFactory;

    protected $guarded = ['id'];

    public function employee(){
        return $this->belongsTo(Employee::class);
    }
}
