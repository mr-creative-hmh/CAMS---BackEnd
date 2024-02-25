<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Http\Resources\Common\RoleResource;
use App\Http\Resources\Common\UserResource;
use App\Http\Services\Auth\UserService;
use App\Traits\ResponseMessage;
use Exception;
use Illuminate\Support\Facades\Hash;

class LoginController extends Controller
{
    use ResponseMessage;

    public function Login(LoginRequest $request)
    {
        // get user with the request email
        $user = UserService::GetUserByEmail($request->email);

        if (!$user || !Hash::check($request->password, $user->password)) {
            return $this->SendMessage("email or password is incorrect.", 401);
        }

        // Eager load the roles relationship
        $user->load('roles');

        $token = $user->createToken("apiToken")->plainTextToken;

        $data = [
            "user" => new UserResource($user),
            "role" => new RoleResource($user->roles->first()),
            "token" => $token
        ];

        return $this->SendResponse("User Logged In!", $data, 200);
    }
}
