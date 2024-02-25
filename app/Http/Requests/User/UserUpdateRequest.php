<?php

namespace App\Http\Requests\User;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class UserUpdateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return True;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            "name" => "sometimes|required|min:3|max:100",
            "email" => "sometimes|required|unique:users,email,{$this->route('id')}",
            "phone" => "sometimes|required",
            "mobile" => "sometimes|required",
            "address" => "sometimes|required",
            "date_of_birth" => "sometimes|required",
            "gender" => "sometimes|required|in:Male,Female,Other"
        ];
    }

    public function messages()
    {
        return [
            "name.required" => "Username is required",
            "name.min" => "Username must be at least 3 characters",
            "name.max" => "Username must not exceed 100 characters",
            "email.required" => "Email is required",
            "email.unique" => "The email has already been taken",
            "phone.required" => "Phone is required",
            "mobile.required" => "Mobile is required",
            "address.required" => "Address is required",
            "date_of_birth.required" => "Date of birth is required",
            "gender.required" => "Gender is required",
            "gender.in" => "Invalid gender value"
        ];
    }

    protected function failedValidation(Validator $validator)
    {
        $response = response()->json([
            'error' => true,
            'message' => "Bad Request",
            'errors' => $validator->errors(),
        ], 422);

        throw new HttpResponseException($response);
    }
}
