<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    public function role()
    {
        return $this->belongsTo(Role::class);
    }

    public function hasRole($role)
    {
        return $this->role->id == $role;
    }

    public function employee()
    {
        return $this->hasOne(Employee::class, 'user_id');
    }

    public function documents()
    {
        return $this->hasOne(Employee::class, 'user_id');
    }

    public function leaveApplication()
    {
        return $this->hasMany(LeaveApplication::class, 'user_id');
    }

    public function isAdmin() {
        return $this->role_id === 1 || $this->role_id === 2;
    }
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */

     protected $fillable = ['name', 'email', 'password', 'job_type', 'work_type', 'role_id', 'city', 'country', 'status'];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        // 'roles' => 'array',
    ];
}
