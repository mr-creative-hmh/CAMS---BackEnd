<?php

namespace App\Http\Controllers\Clinic;

use App\Http\Controllers\Controller;
use App\Http\Requests\Clinic\ClinicCreateRequest;
use App\Http\Requests\Clinic\ClinicUpdateRequest;
use App\Http\Resources\Clinics\ClinicResource;
use App\Http\Services\Clinic\ClinicService;
use App\Models\Clinic\Clinic;
use App\Traits\ResponseMessage;
use Exception;
use Illuminate\Http\Request;

class ClinicController extends Controller
{

    use ResponseMessage;

    public  function __construct()
    {
        $this->middleware("auth:sanctum");
    }

    public function index()
    {

        return ClinicResource::collection(Clinic::all());
    }

    public function show(Clinic $clinic)
    {

        return new ClinicResource($clinic);
    }

    public function store(ClinicCreateRequest $request)
    {

        $created_clinic = ClinicService::CreateClinic(
            $request->name,
            $request->address,
            $request->phone,
            $request->operating_hours,
            $request->category_id,
        );
        $data = new ClinicResource($created_clinic);
        return $this->SendResponse("Clinic Created.", $data, 201);
    }

    public function update(ClinicUpdateRequest $request, Clinic $clinic)
    {

        // Update the user's data using the validated request data
        $Updated_clinic = ClinicService::UpdateClinic($clinic, $request);
        $data = new ClinicResource($Updated_clinic);

        return $this->SendResponse("Clinic Updated.", $data, 200);
    }

    public function destroy(Clinic $clinic)
    {

        ClinicService::DeleteClinic($clinic);
        return $this->SendMessage("CLinic Deleted.", 200);
    }
}
