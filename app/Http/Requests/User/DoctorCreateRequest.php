<?php

namespace App\Http\Requests\User;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class DoctorCreateRequest extends FormRequest
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
            //
            "name" => "required|min:3|max:100",
            "email" => "required|unique:users,email",
            "password" => "required|min:8|max:30",
            "phone" => "required",
            "mobile" => "required",
            "address" => "required",
            "date_of_birth" => "required",
            "gender" => "required|in:Male,Female,Other",
            "specialization_id" => "required|exists:specializations,id",
            "experience" => "sometimes|required",
            "additional_info" => "sometimes"
        ];
    }

    public function messages()
    {
        return [
            "name.required" => "username is required",
            "name.min" => "username must be greater than 3 characters",
            "name.max" => "username must be less than 100 characters",
            "email.required" => "email is required",
            "password.required" => "password is required",
            "password.min" => "password is must be longer than 8 characters",
            "password.max" => "password is must be less than 30 characters",
            "phone.required" => "phone is required",
            "mobile.required" => "mobile is required",
            "address.required" => "address is required",
            "date_of_birth.required" => "date_of_birth is required",
            "gender.required" => "gender is required",
            "specialization_id.required" => "specialization is required",
            "experience.required" => "experience is required",
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
