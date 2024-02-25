<?php

namespace App\Http\Resources\Users;

use App\Http\Resources\Clinics\ClinicResource;
use App\Http\Resources\Common\UserResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ClinicManagerResource extends JsonResource
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
            "User" => new UserResource($this->user),
            "Clinic" => new ClinicResource($this->clinic),
            "AdditionalInfo" => $this->additional_info,
        ];
    }
}
