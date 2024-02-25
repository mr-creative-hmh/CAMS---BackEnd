<?php

namespace App\Http\Resources\Management;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\App;

class MedicalRecordResource extends JsonResource
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
            "Appointment" => new AppointmentResource($this->appointment),
            'MedicalCondition' => $this->medical_condition,
            'Diagnosis' => $this->diagnosis,
            'Prescription' => $this->prescription,
            'FollowUpDate' => $this->follow_up_date,
            'AdditionalNotes' => $this->additional_notes,
            'Active' => $this->active,
        ];
    }
}
