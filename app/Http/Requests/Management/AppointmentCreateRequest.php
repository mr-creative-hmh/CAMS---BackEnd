<?php

namespace App\Http\Requests\Management;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class AppointmentCreateRequest extends FormRequest
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
            'doctor_schedule_id' => 'required|exists:doctor_schedules,id',
            'patient_id' => 'required|exists:patients,id',
            'appointment_date' => 'required|date_format:Y-m-d H:i:s', // Adjust the format as needed
            'appointment_type' => 'required|in:Normal,Follow-Up,Re-Scheduled',
            'appointment_status' => 'required|in:Scheduled,Cancelled,Completed',
            'reason_for_visit' => 'nullable|string|max:255',
        ];
    }

    public function messages()
    {
        return [
            'doctor_schedule_id.required' => 'The doctor schedule is required.',
            'doctor_schedule_id.exists' => 'The selected doctor schedule is invalid.',
            'patient_id.required' => 'The patient is required.',
            'patient_id.exists' => 'The selected patient is invalid.',
            'appointment_date.required' => 'The appointment date is required.',
            'appointment_date.date_format' => 'The appointment date must be in a valid datetime format.',
            'appointment_type.required' => 'The appointment type is required.',
            'appointment_type.in' => 'Invalid appointment type selected.',
            'appointment_status.required' => 'The appointment status is required.',
            'appointment_status.in' => 'Invalid appointment status selected.',
            'reason_for_visit.max' => 'The reason for visit may not be greater than :max characters.',
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
