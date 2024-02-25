<?php

namespace App\Models\Pivot;

use App\Models\Clinic\Clinic;
use App\Models\User\Patient;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ClinicPatient extends Model
{
    use HasFactory;

    protected $fillable = [
        'clinic_id',
        'patient_id',
    ];

    public function clinic()
    {
        return $this->belongsTo(Clinic::class);
    }

    public function patient()
    {
        return $this->belongsTo(Patient::class);
    }
}
