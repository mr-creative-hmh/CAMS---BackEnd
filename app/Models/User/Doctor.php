<?php

namespace App\Models\User;

use App\Models\Clinic\Clinic;
use App\Models\Common\User;
use App\Models\Management\DoctorSchedule;
use App\Models\Management\MedicalRecord;
use App\Models\User\Specialization;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Doctor extends Model
{
    use HasFactory;
    protected $with = ["user"];
    protected $fillable = [
        'user_id',
        'specialization_id',
        'experience',
        'additional_info'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function specialization()
    {
        return $this->belongsTo(Specialization::class);
    }

    public function clinics()
    {
        return $this->belongsToMany(Clinic::class , 'clinic_doctors' , 'doctor_id' , 'clinic_id');
    }

    public function patients()
    {
        return $this->belongsToMany(Patient::class , 'doctor_patients' , 'doctor_id' , 'patient_id');
    }

    public function doctorSchedules()
    {
        return $this->hasMany(DoctorSchedule::class);
    }

    public function medicalrecords()
    {
        return $this->belongsToMany(MedicalRecord::class);
    }
}
