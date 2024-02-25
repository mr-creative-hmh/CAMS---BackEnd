<?php

namespace App\Models\Common;

// use Illuminate\Contracts\Auth\MustVerifyEmail;

use App\Models\User\Doctor;
use App\Models\Common\Role;
use App\Models\User\ClinicManager;
use App\Models\User\Patient;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        "phone",
        "mobile",
        "address",
        "date_of_birth",
        "gender"
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    public function doctor()
    {
        return $this->hasOne(Doctor::class);
    }

    public function patient()
    {
        return $this->hasOne(Patient::class);
    }

    public function clinicmanager()
    {
        return $this->hasOne(ClinicManager::class);
    }


    const ROLE_ADMIN = '1';
    const ROLE_CLINIC_MANAGER = '2';
    const ROLE_DOCTOR = '3';
    const ROLE_PATIENT = '4';

    public function roles()
    {
        return $this->belongsToMany(Role::class, 'role_user', 'user_id', 'role_id');
    }
}
