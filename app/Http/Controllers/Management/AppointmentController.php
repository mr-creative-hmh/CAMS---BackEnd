<?php

namespace App\Http\Controllers\Management;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\Management\AppointmentCreateRequest;
use App\Http\Requests\Management\AppointmentUpdateRequest;
use App\Http\Resources\Management\AppointmentResource;
use App\Http\Services\Management\ManagementService;
use App\Models\Management\Appointment;
use App\Models\Management\DoctorSchedule;
use App\Models\User\Patient;
use App\Traits\ResponseMessage;

class AppointmentController extends Controller
{
    use ResponseMessage;

    public  function __construct()
    {
        $this->middleware("auth:sanctum");
    }

    public function index()
    {

        return AppointmentResource::collection(Appointment::all());
    }

    public function store(AppointmentCreateRequest $request)
    {

        // Determine the doctor associated with the doctor_schedule_id
        $doctor = DoctorSchedule::findOrFail($request->doctor_schedule_id)->doctor;

        // Retrieve the patient associated with the provided patient_id
        $patient = Patient::findOrFail($request->patient_id);

        // Check if the patient is already assigned to the doctor
        if (!$patient->doctors->contains($doctor)) {
            // If not, assign the patient to the doctor
            $patient->doctors()->attach($doctor);
        }

        $result = ManagementService::createAppointment(
            $request->doctor_schedule_id,
            $request->patient_id,
            $request->appointment_date,
            $request->appointment_type,
            $request->appointment_status,
            $request->reason_for_visit,
        );

        // If an existing schedule is found, return it with a status flag
        if ($result['status'] === 'exists') {
            $existingAppointment = new AppointmentResource($result['appointment']);
            return $this->SendResponse("This appointment already exists.", $existingAppointment, 409);
        } elseif ($result['status'] === 'created') {
            $createdAppointment = new AppointmentResource($result['appointment']);
            return $this->SendResponse("Appointment created successfully.", $createdAppointment, 201);
        } elseif ($result['status'] === 'invalid_day') {
            return $this->SendMessage($result['message'], 400);
        }
    }

    public function show(Appointment $appointment)
    {

        if (is_null($appointment)) {
            return $this->SendMessage("appointment is incorrect or Not Exisit.", 404);
        }
        return new AppointmentResource($appointment);
    }

    public function update(AppointmentUpdateRequest $request, Appointment $appointment)
    {

        // Update the Appointment using the validated request data
        if (is_null($appointment)) {
            return $this->SendMessage("appointment is incorrect or Not Exisit.", 404);
        }

        $Updated_appointment = ManagementService::updateAppointment($appointment, $request);

        $data = new AppointmentResource($Updated_appointment);

        return $this->SendResponse("Appointment Updated.", $data, 200);
    }

    public function destroy(Appointment $appointment)
    {
        if (is_null($appointment)) {
            return $this->SendMessage("Appointment is incorrect or Not Exisit.", 404);
        }
        ManagementService::deleteAppointment($appointment);
        return $this->SendMessage("Appointment Deleted.", 200);
    }
}
