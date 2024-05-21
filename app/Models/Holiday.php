<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Holiday extends Model
{
    use HasFactory;
    
    protected $guarded = ['id'];

    const STATUS_RELIGIOUS = 'Religious';
    const STATUS_NATIONAL_HOLIDAYS = 'National Holidays';

    public static function getStatusOptions(){
        return [
            self::STATUS_RELIGIOUS,
            self::STATUS_NATIONAL_HOLIDAYS,
        ];
    }
}
