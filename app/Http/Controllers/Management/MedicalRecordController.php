<?php

namespace App\Http\Controllers\Management;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\Management\MedicalRecordCreateRequest;
use App\Http\Requests\Management\MedicalRecordUpdateRequest;
use App\Http\Resources\Management\MedicalRecordResource;
use App\Http\Services\Management\ManagementService;
use App\Models\Management\Appointment;
use App\Models\Management\MedicalRecord;
use App\Models\User\Patient;
use App\Traits\ResponseMessage;

class MedicalRecordController extends Controller
{
    use ResponseMessage;

    public  function __construct()
    {
        $this->middleware("auth:sanctum");
    }

    public function index()
    {
        return MedicalRecordResource::collection(MedicalRecord::all());
    }

    public function store(MedicalRecordCreateRequest $request)
    {
        $result = ManagementService::createMedicalRecord(

            $request->appointment_id,
            $request->medical_condition,
            $request->diagnosis,
            $request->prescription,
            $request->follow_up_date,
            $request->additional_notes,
            $request->active,
        );

        // If an existing record is found, return it with a status flag
        if ($result['status'] === 'exists') {

            $existrecord = new MedicalRecordResource($result['record']);

            return $this->SendResponse("Medical record for this appointment already exists.", $existrecord, 409);
        } elseif ($result['status'] === 'created') {

            $data = new MedicalRecordResource($result['record']);

            return $this->SendResponse("Medical Record created successfully", $data, 201);
        }
    }

    public function show(MedicalRecord $medicalrecord)
    {

        if (is_null($medicalrecord)) {
            return $this->SendMessage("Medical Record is incorrect or Not Exisit.", 404);
        }
        return new MedicalRecordResource($medicalrecord);
    }

    public function update(MedicalRecordUpdateRequest $request, MedicalRecord $medicalrecord)
    {

        // Update the user's data using the validated request data
        if (is_null($medicalrecord)) {
            return $this->SendMessage("doctor Schedule is incorrect or Not Exisit.", 404);
        }

        $Updated_medicalrecord = ManagementService::updateMedicalRecord($medicalrecord, $request);

        $data = new MedicalRecordResource($Updated_medicalrecord);

        return $this->SendResponse(" Medical Record Updated.", $data, 200);
    }

    public function destroy(MedicalRecord $medicalrecord)
    {
        if (is_null($medicalrecord)) {
            return $this->SendMessage("Medical Record is incorrect or Not Exisit.", 404);
        }
        ManagementService::deleteMedicalRecord($medicalrecord);
        return $this->SendMessage("Medical Record Deleted.", 200);
    }

    public function patientMedicalRecords($patientId)
    {
        // Retrieve the patient
        $patient = Patient::findOrFail($patientId);

        // Retrieve appointments for the patient
        $appointments = $patient->appointments()->get();

        $medicalRecords = collect(); // Initialize an empty collection

        // Iterate over each appointment
        foreach ($appointments as $appointment) {
            // Retrieve medical records associated with the appointment
            $records = $appointment->medicalRecord()->get();

            // Add medical records to the collection
            $medicalRecords = $medicalRecords->concat($records);
        }

        // Return the medical records as a collection of MedicalRecordResource
        return MedicalRecordResource::collection($medicalRecords);
    }

    public function appointmentMedicalRecord($appointmentId)
    {
        // Retrieve the appointment
        $appointment = Appointment::findOrFail($appointmentId);

        // Retrieve the medical record associated with the appointment
        $medicalRecord = $appointment->medicalRecord;

        // Check if a medical record is found
        if ($medicalRecord) {
            // Return the medical record as a resource
            return new MedicalRecordResource($medicalRecord);
        } else {
            // Return a response indicating that no medical record is available
            return response()->json([
                'message' => 'No medical record available for the appointment.',
            ], 404);
        }
    }
}
