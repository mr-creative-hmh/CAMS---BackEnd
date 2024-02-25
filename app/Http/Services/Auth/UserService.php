<?php

namespace App\Http\Services\Auth;

use App\Http\Resources\Common\UserResource;
use App\Models\Common\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserService {

    //User Functions


    //Create User
    public static function CreateUser($name, $email, $password, $phone, $mobile, $address, $date_of_birth, $gender)
    {
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
        return $created_user;
    }


    //Get User By Email
    public static function GetUserByEmail($email)
    {
        return User::where("email",$email)->first();
    }


    //Get User By ID
    public static function GetUserById($id)
    {
        return User::indOrFail($id);
    }


    //Update User
    public static function UpdateUser(User $user , Request $request)
    {

        $userData = $request->validated();
        $user->update($userData);
        return $user;

    }


    //Delete User
    public static function DeleteUser(User $user)
    {
        $user->delete();
    }
}
