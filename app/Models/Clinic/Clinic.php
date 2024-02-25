<?php

namespace App\Models\Clinic;

use App\Models\Management\Appointment;
use App\Models\Management\DoctorSchedule;
use App\Models\User\ClinicManager;
use App\Models\User\Doctor;
use App\Models\User\Patient;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Clinic extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'address',
        'phone',
        'operating_hours',
        'category_id',
    ];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function clinicmans()
    {
        return $this->hasMany(ClinicManager::class);
    }

    public function doctors()
    {
        return $this->belongsToMany(Doctor::class, 'clinic_doctors', 'clinic_id', 'doctor_id');
    }

    public function patients()
    {
        return $this->belongsToMany(Patient::class, 'clinic_patients', 'clinic_id', 'patient_id');
    }

    public function doctorschedules()
    {
        return $this->hasMany(DoctorSchedule::class);
    }

    public function appointments()
    {
        return $this->hasManyThrough(Appointment::class, DoctorSchedule::class);
    }
}
