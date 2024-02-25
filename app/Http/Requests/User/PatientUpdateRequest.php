<?php

namespace App\Http\Requests\User;


use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class PatientUpdateRequest extends FormRequest
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
            "name" => "sometimes|required|min:3|max:100",
            "email" => "sometimes|required|unique:users,email" . $this->route('user->id'),
            "phone" => "sometimes|required",
            "mobile" => "sometimes|required",
            "address" => "sometimes|required",
            "date_of_birth" => "sometimes|required",
            "gender" => "sometimes|required|in:Male,Female,Other",
            "weight" => "sometimes",
            "height" => "sometimes",
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
            "phone.required" => "phone is required",
            "mobile.required" => "mobile is required",
            "address.required" => "address is required",
            "date_of_birth.required" => "date_of_birth is required",
            "gender.required" => "gender is required",
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
