<?php

namespace App\Http\Controllers\User\Role;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\User\ClinicManagerCreateRequest;
use App\Http\Requests\User\ClinicManagerUpdateRequest;
use App\Http\Resources\Users\ClinicManagerResource;
use App\Http\Services\User\RolesService;
use App\Models\User\ClinicManager;
use App\Traits\ResponseMessage;
use Exception;

class ClinicManagerController extends Controller
{
    use ResponseMessage;

    public  function __construct()
    {
        $this->middleware("auth:sanctum");
    }

    public function index()
    {

        return ClinicManagerResource::collection(ClinicManager::all());
    }

    public function store(ClinicManagerCreateRequest $request)
    {

        $created_clinicmanager = RolesService::CreateClinicManager(
            $request->name,
            $request->email,
            $request->password,
            $request->phone,
            $request->mobile,
            $request->address,
            $request->date_of_birth,
            $request->gender,
            $request->clinic_id,
            $request->additional_info
        );

        $token = $created_clinicmanager->user->createToken("apiToken")->plainTextToken;

        $data = [
            "ClinicManager" => new ClinicManagerResource($created_clinicmanager),
            "token" => $token
        ];

        return $this->SendResponse("Clinic Manager Created.", $data, 201);
    }

    public function show(ClinicManager $clinicmanager)
    {

        if (is_null($clinicmanager)) {
            return $this->SendMessage("Clinic Manager is incorrect or Not Exisit.", 404);
        }
        return new ClinicManagerResource($clinicmanager);
    }

    public function update(ClinicManagerUpdateRequest $request, ClinicManager $clinicmanager)
    {

        // Check if the ClinicMan exists
        if (is_null($clinicmanager)) {
            return $this->SendMessage("Clinic Manager is incorrect or does not exist.", 404);
        }

        // Update the ClinicMan's data using the RolesService
        $updatedclinicmanager = RolesService::UpdateClinicManager($clinicmanager, $request);

        $data = new ClinicManagerResource($updatedclinicmanager);

        return $this->SendResponse("Clinic Manager Updated.", $data, 200);
    }

    public function destroy(ClinicManager $clinicmanager)
    {
        // Check if the ClinicMan exists
        if (is_null($clinicmanager)) {
            return $this->SendMessage("Clinic Manager is incorrect or does not exist.", 404);
        }

        RolesService::DeleteClinicManager($clinicmanager);
        return $this->SendMessage("Clinic Manager Deleted.", 200);
    }
}
