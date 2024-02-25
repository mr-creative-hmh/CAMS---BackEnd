<?php

namespace App\Http\Controllers\Pivot;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\Users\PatientResource;
use App\Models\User\Doctor;
use App\Models\User\Patient;
use App\Traits\ResponseMessage;

class DoctorPatientController extends Controller
{
    use ResponseMessage;

    public function doctorPatients($doctorId)
    {
        $doctor = Doctor::findOrFail($doctorId);

        $doctorPatinets = $doctor->patients()->get();

        return PatientResource::collection($doctorPatinets);
    }
}
