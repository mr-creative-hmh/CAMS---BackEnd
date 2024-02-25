<?php

namespace App\Http\Controllers\User\Role;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\User\SpecializationCreateRequest;
use App\Http\Requests\User\SpecializationUpdateRequest;
use App\Http\Resources\Users\SpecializationResource;
use App\Http\Services\User\RolesService;
use App\Models\User\Specialization;
use App\Traits\ResponseMessage;
use Exception;

class SpecializationController extends Controller
{

    use ResponseMessage;

    public  function __construct()
    {
        $this->middleware("auth:sanctum");
    }

    public function index()
    {

        return SpecializationResource::collection(Specialization::all());

    }

    public function show(Specialization $specialization)
    {

        if (is_null($specialization)) {
            return $this->SendMessage("Specialization is incorrect or Not Exisit.", 404);
        }
        return new SpecializationResource($specialization);

    }

    public function store(SpecializationCreateRequest $request)
    {

        $created_specialization = RolesService::CreateSpecialization(
            $request->name,
        );
        $data = new SpecializationResource($created_specialization);
        return $this->SendResponse("Specialization Created.", $data, 201);


    }

    public function update(SpecializationUpdateRequest $request, Specialization $specialization)
    {

        // Check if the Specialization exists
        if (is_null($specialization)) {
            return $this->SendMessage("Specialization is incorrect or Not Exisit.", 404);
        }

        $Updated_specialization = RolesService::UpdateSpecialization( $specialization ,$request);
        $data = new SpecializationResource($Updated_specialization);

        return $this->SendResponse("Specialization Updated.", $data, 200);

    }

    public function destroy(Specialization $specialization)
    {
        // Check if the Specialization exists
        if (is_null($specialization)) {
            return $this->SendMessage("Specialization is incorrect or Not Exisit.", 404);
        }

        RolesService::DeleteSpecialization($specialization);
        return $this->SendMessage("Specialization Deleted.", 200);

    }
}
