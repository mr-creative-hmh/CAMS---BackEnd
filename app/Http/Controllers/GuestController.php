<?php

namespace App\Http\Controllers;

use App\Http\Requests\User\PatientCreateRequest;
use App\Http\Resources\Clinics\CategoryResource;
use App\Http\Resources\Clinics\ClinicResource;
use App\Http\Resources\Management\DoctorScheduleResource;
use App\Http\Resources\Users\DoctorResource;
use App\Http\Resources\Users\PatientResource;
use App\Http\Services\User\RolesService;
use App\Models\Clinic\Category;
use App\Models\Clinic\Clinic;
use App\Models\Management\DoctorSchedule;
use App\Models\User\Doctor;
use App\Traits\ResponseMessage;
use Illuminate\Http\Request;

class GuestController extends Controller
{
    use ResponseMessage;
    // Method to get all clinic categories
    public function getClinicsCategories()
    {
        $categories = Category::whereHas('clinics')->get();
        return CategoryResource::collection($categories);
    }

    // Method to get clinics of a specific category
    public function getClinicsOfCategory($categoryId)
    {
        $category = Category::findOrFail($categoryId);
        $clinics = $category->clinics()->get();
        return ClinicResource::collection($clinics);
    }

    // Method to get doctors of a clinic
    public function getDoctorsOfClinic($clinicId)
    {
        $clinic = Clinic::findOrFail($clinicId);
        $doctors = $clinic->doctors()->get();
        return DoctorResource::collection($doctors);
    }

    // Method to get schedules of a doctor in a clinic
    public function getSchedulesOfDoctorInClinic($doctorId, $clinicId)
    {
        $schedules = DoctorSchedule::where('doctor_id', $doctorId)
            ->where('clinic_id', $clinicId)
            ->get();

        return DoctorScheduleResource::collection($schedules);
    }

    public function CreatePatientUser(PatientCreateRequest $request)
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

        return $this->SendMessage("Registered Successfully!", 201);
    }
}
