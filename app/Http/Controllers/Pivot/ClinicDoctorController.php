<?php

namespace App\Http\Controllers\Pivot;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\Clinics\ClinicResource;
use App\Http\Resources\Users\DoctorResource;
use App\Models\Clinic\Clinic;
use App\Models\Pivot\ClinicDoctor;
use App\Models\User\Doctor;
use App\Traits\ResponseMessage;

class ClinicDoctorController extends Controller
{
    use ResponseMessage;

    // public  function __construct()
    // {
    //     $this->middleware("auth:sanctum");
    // }

    public function clinicDoctors($clinicId)
    {
        $clinic = Clinic::with('doctors')->find($clinicId);

        if (!$clinic) {
            return $this->SendMessage('Clinic not found', 404);
        }

        $clinicDoctors = DoctorResource::collection($clinic->doctors);

        return $this->SendResponse('Clinic doctors retrieved successfully', $clinicDoctors, 200);
    }

    public function unassignedDoctors($clinicId)
    {
        // Find the clinic
        $clinic = Clinic::find($clinicId);

        if (!$clinic) {
            return $this->SendMessage('Clinic not found', 404);
        }

        // Retrieve all doctors assigned to the clinic
        $clinicDoctors = ClinicDoctor::where('clinic_id', $clinicId)->pluck('doctor_id');

        // Retrieve unassigned doctors by excluding clinic doctors
        $unassignedDoctors = Doctor::whereNotIn('id', $clinicDoctors)->get();

        // Transform unassigned doctors into DoctorResource collection
        $unassignedDoctorsResource = DoctorResource::collection($unassignedDoctors);

        return $this->SendResponse('Unassigned doctors retrieved successfully', $unassignedDoctorsResource, 200);
    }

    public function assignDoctor($doctorId, $clinicId)
    {
        // Check if the clinic doctor record already exists
        $existingRecord = ClinicDoctor::where('doctor_id', $doctorId)
            ->where('clinic_id', $clinicId)
            ->exists();

        // If the clinic doctor record already exists, return a message indicating that the doctor is already assigned to the clinic
        if ($existingRecord) {
            return $this->SendMessage('Doctor is already assigned to this clinic', 409);
        }

        // Create a new clinic doctor record
        ClinicDoctor::create([
            'doctor_id' => $doctorId,
            'clinic_id' => $clinicId,
        ]);

        // Return a success message indicating that the doctor has been assigned to the clinic
        return $this->SendMessage('Doctor assigned to clinic successfully', 201);
    }

    public function unAssignDoctor($doctorId, $clinicId)
    {
        $clinicDoctor = ClinicDoctor::where('doctor_id', $doctorId)->where('clinic_id', $clinicId)->first();

        if (!$clinicDoctor) {
            return $this->SendMessage('Doctor not assigned to this clinic', 404);
        }

        $clinicDoctor->delete();

        return $this->SendMessage('Doctor unassigned from clinic successfully', 200);
    }


    public function doctorClinics($doctorId)
    {
        // Retrieve the doctor
        $doctor = Doctor::findOrFail($doctorId);

        // Retrieve the clinics associated with the doctor
        $doctorClinics = $doctor->clinics()->get();

        // Return the clinics as a collection of ClinicResource
        return ClinicResource::collection($doctorClinics);
    }
}
