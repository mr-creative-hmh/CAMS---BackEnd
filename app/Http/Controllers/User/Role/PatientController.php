<?php

namespace App\Http\Controllers\User\Role;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\User\PatientCreateRequest;
use App\Http\Requests\User\PatientUpdateRequest;
use App\Http\Resources\Users\PatientResource;
use App\Http\Services\User\RolesService;
use App\Models\User\Patient;
use App\Traits\ResponseMessage;
use Exception;

class PatientController extends Controller
{
    use ResponseMessage;

    public  function __construct()
    {
        $this->middleware("auth:sanctum");
    }

    public function index()
    {
        return PatientResource::collection(Patient::all());
    }

    public function store(PatientCreateRequest $request)
    {

        $created_patient = RolesService::CreatePatient(
            $request->name,
            $request->email,
            $request->password,
            $request->phone,
            $request->mobile,
            $request->address,
            $request->date_of_birth,
            $request->gender,
            $request->weight,
            $request->height,
            $request->additional_info
        );

        $token = $created_patient->user->createToken("apiToken")->plainTextToken;

        $data = [
            "Patient" => new PatientResource($created_patient),
            "token" => $token
        ];

        return $this->SendResponse("Patient Created.", $data, 201);
    }

    public function show(Patient $patient)
    {

        if (is_null($patient)) {
            return $this->SendMessage("Patient is incorrect or Not Exisit.", 404);
        }
        return new PatientResource($patient);
    }

    public function update(PatientUpdateRequest $request, Patient $patient)
    {

        // Check if the Patient exists
        if (is_null($patient)) {
            return $this->SendMessage("Patient is incorrect or does not exist.", 404);
        }

        // Update the patient's data using the RolesService
        $updatedPatient = RolesService::UpdatePatient($patient, $request);

        $data = new PatientResource($updatedPatient);

        return $this->SendResponse("Patient Updated.", $data, 200);
    }

    public function destroy(Patient $patient)
    {
        // Check if the Patient exists
        if (is_null($patient)) {
            return $this->SendMessage("Patient is incorrect or does not exist.", 404);
        }

        RolesService::DeletePatient($patient);
        return $this->SendMessage("Patient Deleted.", 200);
    }
}
