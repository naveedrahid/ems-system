<?php

namespace App\Models\JobModels;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Job extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $guarded = ['id'];

    public function candidate(){
        return $this->hasMany(Candidate::class);
    }
}
