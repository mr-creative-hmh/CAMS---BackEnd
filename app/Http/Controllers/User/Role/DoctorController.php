<?php

namespace App\Http\Controllers\User\Role;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\User\DoctorCreateRequest;
use App\Http\Requests\User\DoctorUpdateRequest;
use App\Http\Resources\Clinics\ClinicResource;
use App\Http\Resources\Users\DoctorResource;
use App\Http\Services\User\RolesService;
use App\Models\User\Doctor;
use App\Traits\ResponseMessage;
use Exception;
use PhpParser\Comment\Doc;

class DoctorController extends Controller
{
    use ResponseMessage;

    public  function __construct()
    {
        $this->middleware("auth:sanctum");
    }

    public function index()
    {
        return DoctorResource::collection(Doctor::all());
    }

    public function store(DoctorCreateRequest $request)
    {

        $created_doctor = RolesService::CreateDoctor(
            $request->name,
            $request->email,
            $request->password,
            $request->phone,
            $request->mobile,
            $request->address,
            $request->date_of_birth,
            $request->gender,
            $request->specialization_id,
            $request->experience,
            $request->additional_info
        );

        $token = $created_doctor->user->createToken("apiToken")->plainTextToken;

        $data = [
            "Doctor" => new DoctorResource($created_doctor),
            "token" => $token
        ];

        return $this->SendResponse("Doctor Created.", $data, 201);
    }

    public function show(Doctor $doctor)
    {

        if (is_null($doctor)) {
            return $this->SendMessage("Doctor is incorrect or Not Exisit.", 404);
        }
        return new DoctorResource($doctor);
    }

    public function update(DoctorUpdateRequest $request, Doctor $doctor)
    {

        // Check if the doctor exists
        if (is_null($doctor)) {
            return $this->SendMessage("Doctor is incorrect or does not exist.", 404);
        }

        // Update the doctor's data using the RolesService
        $updatedDoctor = RolesService::UpdateDoctor($doctor, $request);

        $data = new DoctorResource($updatedDoctor);

        return $this->SendResponse("Doctor Updated.", $data, 200);
    }

    public function destroy(Doctor $doctor)
    {
        // Check if the doctor exists
        if (is_null($doctor)) {
            return $this->SendMessage("Doctor is incorrect or does not exist.", 404);
        }

        RolesService::DeleteDoctor($doctor);
        return $this->SendMessage("Doctor Deleted.", 200);
    }
}
