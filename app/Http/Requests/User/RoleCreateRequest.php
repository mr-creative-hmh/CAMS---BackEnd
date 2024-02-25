<?php

namespace App\Http\Requests\User;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class RoleCreateRequest extends FormRequest
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
            "name" => "required|min:3|max:100|unique:roles,name",
        ];
    }
    public function messages()
    {
        return [
            "name.required" => "role name is required",
            "name.unique" => "role already exisit",
            "name.min" => "role name must be greater than 3 characters",
            "name.max" => "role name must be less than 100 characters",
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
