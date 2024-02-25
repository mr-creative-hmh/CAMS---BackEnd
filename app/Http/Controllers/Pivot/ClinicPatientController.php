<?php

namespace App\Http\Controllers\Pivot;

use App\Http\Controllers\Controller;
use App\Http\Resources\Users\PatientResource;
use App\Models\Clinic\Clinic;
use App\Models\Pivot\ClinicPatient;
use App\Models\User\Patient;
use App\Traits\ResponseMessage;
use Illuminate\Http\Request;

class ClinicPatientController extends Controller
{
    //
    use ResponseMessage;

    public function clinicPatients($clinicId)
    {
        $clinic = Clinic::with('patients')->find($clinicId);

        if (!$clinic) {
            return $this->SendMessage('Clinic not found', 404);
        }

        $clinicPatients = PatientResource::collection($clinic->patients);

        return $this->SendResponse('Clinic patients retrieved successfully', $clinicPatients, 200);
    }

    public function unassignedPatients($clinicId)
    {
        // Find the clinic
        $clinic = Clinic::find($clinicId);

        if (!$clinic) {
            return $this->SendMessage('Clinic not found', 404);
        }

        // Retrieve all doctors assigned to the clinic
        $clinicPatients = ClinicPatient::where('clinic_id', $clinicId)->pluck('patient_id');

        // Retrieve unassigned doctors by excluding clinic doctors
        $unassignedPatients = Patient::whereNotIn('id', $clinicPatients)->get();

        // Transform unassigned doctors into DoctorResource collection
        $unassignedPatientsResource = PatientResource::collection($unassignedPatients);

        return $this->SendResponse('Unassigned patients retrieved successfully', $unassignedPatientsResource, 200);
    }

    public function assignPatient($patientId, $clinicId)
    {
        // Check if the clinic doctor record already exists
        $existingRecord = ClinicPatient::where('patient_id', $patientId)
            ->where('clinic_id', $clinicId)
            ->exists();

        // If the clinic doctor record already exists, return a message indicating that the doctor is already assigned to the clinic
        if ($existingRecord) {
            return $this->SendMessage('Patient is already assigned to this clinic', 409);
        }

        // Create a new clinic doctor record
        ClinicPatient::create([
            'patient_id' => $patientId,
            'clinic_id' => $clinicId,
        ]);

        // Return a success message indicating that the doctor has been assigned to the clinic
        return $this->SendMessage('Patient assigned to clinic successfully', 201);
    }

    public function unAssignPatient($patientId, $clinicId)
    {
        $clinicPatient = ClinicPatient::where('patient_id', $patientId)->where('clinic_id', $clinicId)->first();

        if (!$clinicPatient) {
            return $this->SendMessage('Patiet not assigned to this clinic', 404);
        }

        $clinicPatient->delete();

        return $this->SendMessage('Patiet unassigned from clinic successfully', 200);
    }
}
