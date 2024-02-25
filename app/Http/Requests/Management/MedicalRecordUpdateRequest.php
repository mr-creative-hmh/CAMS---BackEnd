<?php

namespace App\Http\Requests\Management;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class MedicalRecordUpdateRequest extends FormRequest
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
    public function rules()
    {
        return [
            'appointment_id' => 'sometimes|required|exists:appointments,id',
            'medical_condition' => 'nullable|string',
            'diagnosis' => 'nullable|string',
            'prescription' => 'nullable|string',
            'follow_up_date' => 'nullable|date',
            'additional_notes' => 'nullable|string',
            'active' => 'sometimes|boolean',
        ];
    }

    public function messages()
    {
        return [
            'appointment_id.required' => 'The appointment is required.',
            'appointment_id.exists' => 'The selected appointment does not exist.',
            'follow_up_date.date' => 'The follow-up date must be a valid date.',
            'active.boolean' => 'The active field must be a boolean value.',
        ];
    }


    protected function failedValidation(Validator $validator)
    {
        $response = response()->json([
            'error' => true,
            'message' => 'Bad Request',
            'errors' => $validator->errors(),
        ], 422);

        throw new HttpResponseException($response);
    }
}
