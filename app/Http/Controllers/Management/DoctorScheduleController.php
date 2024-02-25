<?php

namespace App\Http\Controllers\Management;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\Management\DoctorScheduleCreateRequest;
use App\Http\Requests\Management\DoctorScheduleUpdateRequest;
use App\Http\Resources\Management\DoctorScheduleResource;
use App\Http\Services\Management\ManagementService;
use App\Models\Clinic\Clinic;
use App\Models\Management\DoctorSchedule;
use App\Traits\ResponseMessage;
use Exception;

class DoctorScheduleController extends Controller
{

    use ResponseMessage;

    public  function __construct()
    {
        $this->middleware("auth:sanctum");
    }

    public function index()
    {

        return DoctorScheduleResource::collection(DoctorSchedule::all());
    }


    public function store(DoctorScheduleCreateRequest $request)
    {

        $result = ManagementService::createSchedules(
            $request->doctor_id,
            $request->clinic_id,
            $request->day_of_week,
            $request->start_time,
            $request->end_time,
            $request->appointment_duration,
        );

        // If an existing schedule is found, return it with a status flag
        if ($result['status'] === 'exists') {

            $existSchedule = new DoctorScheduleResource($result['schedule']);

            return $this->SendResponse("This schedule already exists.", $existSchedule, 409);
        }

        // If an existing schedule conflict, return it with a status flag
        if ($result['status'] === 'conflict') {

            $conflictSchedule = new DoctorScheduleResource($result['schedule']);

            return $this->SendResponse("This schedule conflict with other exist one.", $conflictSchedule, 409);
        } elseif ($result['status'] === 'created') {

            $data = new DoctorScheduleResource($result['schedule']);

            return $this->SendResponse("Doctor schedule created successfully.", $data, 201);
        }
    }

    public function show(DoctorSchedule $doctorschedule)
    {

        if (is_null($doctorschedule)) {
            return $this->SendMessage("Doctor schedule is incorrect or Not Exisit.", 404);
        }
        return new DoctorScheduleResource($doctorschedule);
    }

    public function update(DoctorScheduleUpdateRequest $request, DoctorSchedule $doctorschedule)
    {

        // Update the user's data using the validated request data
        if (is_null($doctorschedule)) {
            return $this->SendMessage("doctor Schedule is incorrect or Not Exisit.", 404);
        }

        $Updated_doctorschedule = ManagementService::updateSchedule($doctorschedule, $request);

        $data = new DoctorScheduleResource($Updated_doctorschedule);

        return $this->SendResponse("Doctor schedule Updated.", $data, 200);
    }

    public function destroy(DoctorSchedule $doctorschedule)
    {
        if (is_null($doctorschedule)) {
            return $this->SendMessage("doctor Schedule is incorrect or Not Exisit.", 404);
        }
        ManagementService::deleteSchedule($doctorschedule);
        return $this->SendMessage("Doctor Schedule Deleted.", 200);
    }

    public function clinicDoctorSchedules($clinicId)
    {
        $clinic = Clinic::findOrFail($clinicId);

        $clinicDoctorschedules = $clinic->doctorSchedules()->get();

        return DoctorScheduleResource::collection($clinicDoctorschedules);
    }
}
