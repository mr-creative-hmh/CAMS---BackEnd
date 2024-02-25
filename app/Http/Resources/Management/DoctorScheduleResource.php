<?php

namespace App\Http\Resources\Management;

use App\Http\Resources\Clinics\ClinicResource;
use App\Http\Resources\Users\DoctorResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class DoctorScheduleResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            "ID" => $this->id,
            "Doctor" => new DoctorResource($this->doctor),
            "Clinic" => New ClinicResource($this->clinic),
            "DayOfWeek" => $this->day_of_week,
            "StartTime" => $this->start_time,
            "EndTime" => $this->end_time,
            "AppointmentDuration" => $this->appointment_duration,
        ];
    }
}
