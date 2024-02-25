<?php

namespace App\Http\Resources\Management;

use App\Http\Resources\Clinics\ClinicResource;
use App\Http\Resources\Users\DoctorResource;
use App\Http\Resources\Users\PatientResource;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AppointmentResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {

        $appointmentDate = Carbon::parse($this->appointment_date);

        return [
            "ID" => $this->id,
            "DoctorSchedule" => new DoctorScheduleResource($this->doctorschedule),
            "Patient" => new PatientResource($this->patient),
            "AppointmentDate" => $appointmentDate->toDateString(),
            "AppointmentTime" => $appointmentDate->format('h:i A'),
            "AppointmentType" => $this->appointment_type,
            "AppointmentStatus" => $this->appointment_status,
            "ReasonForVisit" => $this->reason_for_visit,
        ];
    }
}
