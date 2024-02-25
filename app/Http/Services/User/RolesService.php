<?php

namespace App\Http\Services\User;

use App\Models\Common\Role;
use App\Models\Common\User;
use App\Models\User\ClinicManager;
use App\Models\User\Doctor;
use App\Models\User\Patient;
use App\Models\User\Specialization;
use App\Traits\ResponseMessage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;

class RolesService
{

    use ResponseMessage;

    // Role Functions

    //Create Role

    public static function CreateRole($name)
    {
        $created_role = Role::Create([
            "name" => $name
        ]);
        return $created_role;
    }


    //Update Role
    public static function UpdateRole(Role $role, Request $request)
    {

        $roled = $request->validated();
        $role->update($roled);
        return $role;
    }


    //Delete Role
    public static function DeleteRole(Role $role)
    {
        $role->delete();
    }

    //Specialization Functions

    //Create Specialization
    public static function CreateSpecialization($name)
    {
        $created_specialization = Specialization::Create([
            "name" => $name
        ]);
        return $created_specialization;
    }

    //Update Specialization
    public static function UpdateSpecialization(Specialization $specialization, Request $request)
    {

        $specializationdata = $request->validated();
        $specialization->update($specializationdata);
        return $specialization;
    }

    //Delete Specialization
    public static function DeleteSpecialization(Specialization $specialization)
    {
        $specialization->delete();
    }

    //Doctor Functions

    //Create Doctor
    public static function CreateDoctor($name, $email, $password, $phone, $mobile, $address, $date_of_birth, $gender, $specialization_id, $experience, $additional_info)
    {
        //DB::beginTransaction();
        $created_user = User::Create([
            "name" => $name,
            "email" => $email,
            "password" => Hash::make($password),
            "phone" => $phone,
            "mobile" => $mobile,
            "address" => $address,
            "date_of_birth" => $date_of_birth,
            "gender" => $gender
        ]);

        $created_user->roles()->attach(User::ROLE_DOCTOR);

        $created_doctor = Doctor::Create([
            "user_id" => $created_user->id,
            "specialization_id" => $specialization_id,
            "experience" => $experience,
            "additional_info" => $additional_info
        ]);

        //DB::commit();
        return $created_doctor;
        //DB::rollBack();
    }

    //Update Doctor
    public static function UpdateDoctor(Doctor $doctor, Request $request)
    {
        $doctorData = $request->validated();

        // Find the associated user and update its data
        $user = $doctor->user;

        // Update the user's data
        $user->update($doctorData);

        // Update the doctor's data
        $doctor->update($doctorData);

        return $doctor;
    }



    //Delete Doctor
    public static function DeleteDoctor(Doctor $doctor)
    {
        $user = $doctor->user;
        // First, delete the doctor record from the 'doctors' table
        $doctor->delete();
        // Next, delete the corresponding user record from the 'users' table
        $user->delete();
    }


    //Patient Functions

    //Create Patient
    public static function CreatePatient($name, $email, $password, $phone, $mobile, $address, $date_of_birth, $gender, $weight, $height, $additional_info)
    {

        //DB::beginTransaction();
        $created_user = User::Create([
            "name" => $name,
            "email" => $email,
            "password" => Hash::make($password),
            "phone" => $phone,
            "mobile" => $mobile,
            "address" => $address,
            "date_of_birth" => $date_of_birth,
            "gender" => $gender
        ]);

        $created_user->roles()->attach(User::ROLE_PATIENT);

        $created_patient = Patient::Create([
            "user_id" => $created_user->id,
            "weight" => $weight,
            "height" => $height,
            "additional_info" => $additional_info
        ]);
        //DB::commit();
        return $created_patient;
        //DB::rollBack();
    }

    //Update Patient
    public static function UpdatePatient(Patient $patient, Request $request)
    {
        $patientData = $request->validated();

        // Find the associated user and update its data
        $user = $patient->user;

        // Update the user's data
        $user->update($patientData);

        // Update the patient's data
        $patient->update($patientData);

        return $patient;
    }



    //Delete Patient
    public static function DeletePatient(Patient $patient)
    {
        $user = $patient->user;
        // First, delete the doctor record from the 'doctors' table
        $patient->delete();
        // Next, delete the corresponding user record from the 'users' table
        $user->delete();
    }


    //ClinicMan Functions

    //Create ClinicMan
    public static function CreateClinicManager($name, $email, $password, $phone, $mobile, $address, $date_of_birth, $gender, $clinic_id,  $additional_info)
    {
        //DB::beginTransaction();
        $created_user = User::Create([
            "name" => $name,
            "email" => $email,
            "password" => Hash::make($password),
            "phone" => $phone,
            "mobile" => $mobile,
            "address" => $address,
            "date_of_birth" => $date_of_birth,
            "gender" => $gender
        ]);

        $created_user->roles()->attach(User::ROLE_CLINIC_MANAGER);

        $created_clinicmanager = ClinicManager::Create([
            "user_id" => $created_user->id,
            "clinic_id" => $clinic_id,
            "additional_info" => $additional_info
        ]);

        //DB::commit();
        return $created_clinicmanager;
        //DB::rollBack();
    }

    //Update ClinicMan
    public static function UpdateClinicManager(ClinicManager $clinicmanager, Request $request)
    {
        $clinicmanagerData = $request->validated();

        // Find the associated user and update its data
        $user = $clinicmanager->user;

        $user->update($clinicmanagerData);

        // Update the ClinicMan's data
        $clinicmanager->update($clinicmanagerData);

        return $clinicmanager;
    }



    //Delete ClinicMan
    public static function DeleteClinicManager(ClinicManager $clinicManager)
    {
        $user = $clinicManager->user;
        // First, delete the doctor record from the 'doctors' table
        $clinicManager->delete();
        // Next, delete the corresponding user record from the 'users' table
        $user->delete();
    }
}
