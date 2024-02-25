<?php

namespace App\Http\Resources\Common;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
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
            "Email" => $this->email,
            "Phone" => $this->phone,
            "Mobile" => $this->mobile,
            "Address" => $this->address,
            "DateOfBirth" => $this->date_of_birth,
            "Gender" => $this->gender,
            "Role" => new RoleResource($this->roles->first())
        ];
    }
}
