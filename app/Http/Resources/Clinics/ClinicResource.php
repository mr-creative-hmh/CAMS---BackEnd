<?php

namespace App\Http\Resources\Clinics;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ClinicResource extends JsonResource
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
            "Name" => $this->name,
            "Address" => $this->address,
            "Phone" => $this->phone,
            "OpenHours" => $this->operating_hours,
            "Category" => new CategoryResource($this->category),
        ];
    }
}
