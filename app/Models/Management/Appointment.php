<?php

namespace App\Models\Management;

use App\Models\User\Patient;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Appointment extends Model
{
    use HasFactory;

    protected $fillable = [
        'doctor_schedule_id',
        'patient_id',
        'appointment_date',
        'appointment_type',
        'appointment_status',
        'reason_for_visit',
    ];

    public function doctorSchedule()
    {
        return $this->belongsTo(DoctorSchedule::class);
    }

    public function patient()
    {
        return $this->belongsTo(Patient::class);
    }

    public function clinic()
    {
        return $this->doctorSchedule->clinic();
    }

    public function medicalrecord()
    {
        return $this->hasOne(MedicalRecord::class);
    }
}
