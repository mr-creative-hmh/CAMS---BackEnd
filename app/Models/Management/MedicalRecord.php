<?php

namespace App\Models\Management;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MedicalRecord extends Model
{
    use HasFactory;

    protected $fillable = [
        'appointment_id',
        'medical_condition',
        'diagnosis',
        'prescription',
        'follow_up_date',
        'additional_notes',
        'active',
    ];

    public function doctor()
    {
        return $this->appointment->doctorSchedule->doctor();
    }

    public function patient()
    {
        return $this->appointment->patient();
    }

    public function appointment()
    {
        return $this->belongsTo(Appointment::class);
    }
}
