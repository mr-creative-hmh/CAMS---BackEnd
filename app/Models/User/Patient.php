<?php

namespace App\Models\User;

use App\Models\Clinic\Clinic;
use App\Models\Common\User;
use App\Models\Management\Appointment;
use App\Models\Management\MedicalRecord;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Patient extends Model
{
    use HasFactory;

    protected $with = ["user"];
    protected $fillable = [
        'user_id',
        'weight',
        'height',
        'additional_info'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function doctors()
    {
        return $this->belongsToMany(Doctor::class, 'doctor_patients', 'patient_id', 'doctor_id');
    }

    public function clinics()
    {
        return $this->belongsToMany(Clinic::class, 'clinic_patients', 'patient_id', 'clinic_id');
    }

    public function appointments()
    {
        return $this->hasMany(Appointment::class);
    }

    public function medicalrecords()
    {
        return $this->belongsToMany(MedicalRecord::class);
    }
}
