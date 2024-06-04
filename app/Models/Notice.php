<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Notice extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    public static function getStatusOptions(){
        return [
            'active' => 'Active',
            'deactive' => 'Deactive',
        ];
    }

    public function department()
    {
        return $this->belongsTo(Department::class);
    }    
}
