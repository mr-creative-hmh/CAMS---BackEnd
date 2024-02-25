<?php

namespace App\Http\Requests\Clinic;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class ClinicUpdateRequest extends FormRequest
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
            "address" => "sometimes|required",
            "phone" => "sometimes|required",
            "operating_hours" => "sometimes|required",
            "category_id" => "sometimes|required",
        ];
    }
    public function messages()
    {
        return [
            "name.required" => "clinic name is required",
            "name.min" => "clinic name must be greater than 3 characters",
            "name.max" => "clinic name must be less than 100 characters",
            "phone.required" => "clinic phone is required",
            "address.required" => "clinic address is required",
            "operating_hours.required" => "clinic operating hours is required",
            "category_id.required" => "clinic category  is required",
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
