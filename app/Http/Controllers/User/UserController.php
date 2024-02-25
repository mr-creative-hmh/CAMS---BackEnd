<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Http\Requests\User\UserUpdateRequest;
use App\Http\Resources\Clinics\ClinicResource;
use App\Http\Resources\Common\UserResource;
use App\Http\Services\Auth\UserService;
use App\Models\Common\User;
use App\Models\User\ClinicManager;
use App\Models\User\Doctor;
use App\Models\User\Patient;
use App\Traits\ResponseMessage;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    use ResponseMessage;

    public  function __construct()
    {
        $this->middleware("auth:sanctum");
    }

    public function index()
    {

        return UserResource::collection(User::all());
    }

    public function show(User $user)
    {

        // if($user->roles->first()) {
        //     return $this->sendMessage("Your don't have permission", 401);
        // }

        if (is_null($user)) {
            return $this->SendMessage("User is incorrect or Not Exisit.", 404);
        }
        return new UserResource($user);
    }

    public function update(UserUpdateRequest $request, User $user)
    {

        // Update the user's data using the validated request data
        if (is_null($user)) {
            return $this->SendMessage("User is incorrect or Not Exisit.", 404);
        }
        $Updated_user = UserService::UpdateUser($user, $request);

        $data = new UserResource($Updated_user);

        return $this->SendResponse("User Updated.", $data, 200);
    }

    public function destroy(User $user)
    {
        // Update the user's data using the validated request data
        if (is_null($user)) {
            return $this->SendMessage("User is incorrect or Not Exisit.", 404);
        }

        UserService::DeleteUser($user);
        return $this->SendMessage("User Deleted.", 200);
    }

    public function changePassword(Request $request, User $user)
    {
        // Validate the request
        $request->validate([
            'current_password' => 'required|string'
        ]);

        // Check if the current password matches
        if (!Hash::check($request->current_password, $user->password)) {
            return $this->SendMessage("Entered password is incorrect.", 400);
        }

        $request->validate([
            'new_password' => 'required|string|min:8'
        ]);
        // Change password
        $user->password = Hash::make($request->new_password);
        $user->save();

        return $this->SendMessage("Password changed successfully.", 200);
    }

    public function changePasswordAdmin(Request $request, $userId)
    {
        // Find the user by ID
        $user = User::find($userId);

        if (!$user) {
            return $this->SendMessage("User not found.", 404);
        }

        // Validate the request
        $request->validate([
            'new_password' => 'required|string|min:8',
        ]);

        // Change password
        $user->password = Hash::make($request->new_password);
        $user->save();

        return $this->SendResponse("Password changed successfully.", $user, 200);
    }

    public function getAdditionalInfo($userId)
    {
        $user = User::find($userId);

        if (!$user) {
            return response()->json(['error' => 'User not found'], 404);
        }
        $userRole = $user->roles->first()->id;
        switch ($userRole) {
            case User::ROLE_DOCTOR:
                $Info = Doctor::where('user_id', $userId)->first();
                $additionalInfo = [
                    "ID" => $Info->id,
                    'specialization' => $Info->specialization,
                    'experience' => $Info->experience,
                    'additional_info' => $Info->additional_info,
                ];
                break;
            case User::ROLE_PATIENT:
                $Info = Patient::where('user_id', $userId)->first();
                $additionalInfo = [
                    "ID" => $Info->id,
                    'weight' => $Info->weight,
                    'height' => $Info->height,
                    'additional_info' => $Info->additional_info,
                ];
                break;
            case User::ROLE_CLINIC_MANAGER:
                $Info = ClinicManager::where('user_id', $userId)->first();
                $additionalInfo = [
                    "ID" => $Info->id,
                    "Clinic" => new ClinicResource($Info->clinic),
                    'additional_info' => $Info->additional_info,
                ];
                break;
            default:
                return response()->json(['error' => 'Role not recognized'], 400);
        }

        if (!$additionalInfo) {
            return response()->json(['error' => 'Additional info not found'], 404);
        }

        return response()->json($additionalInfo);
    }
}
