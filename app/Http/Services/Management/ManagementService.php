<?php

namespace App\Http\Services\Management;

use App\Http\Resources\Clinics\CategoryResource;
use App\Http\Resources\Clinics\ClinicResource;
use App\Http\Resources\Management\DoctorScheduleResource;
use App\Http\Resources\Users\DoctorResource;
use App\Models\Clinic\Category;
use App\Models\Clinic\Clinic;
use App\Models\Management\Appointment;
use App\Models\Management\DoctorSchedule;
use App\Models\Management\MedicalRecord;
use App\Traits\ResponseMessage;
use Carbon\Carbon;
use Carbon\CarbonInterval;
use Illuminate\Http\Request;
use Illuminate\Database\QueryException; // Import QueryException for database errors

class ManagementService
{
    use ResponseMessage;
    // DoctorSchedule Section

    // Create DoctorSchedule
    public static function createSchedules($doctor_id, $clinic_id, $day_of_week, $start_time, $end_time, $appointment_duration)
    {

        // Cast start_time and end_time to a time format
        $start_time = Carbon::parse($start_time)->format('H:i:s');
        $end_time = Carbon::parse($end_time)->format('H:i:s');

        // Check if a schedule with the same data already exists
        $existingSchedules = DoctorSchedule::where('doctor_id', $doctor_id)
            ->where('clinic_id', $clinic_id)
            ->where('day_of_week', $day_of_week)
            ->get();

        foreach ($existingSchedules as $schedule) {

            //Exisit one

            if ($start_time == $schedule->start_time && $end_time == $schedule->end_time) {
                // Prevent creation due to exact match
                return ['status' => 'exists', 'schedule' => $schedule];
            }
            // Check if the new schedule's start and end times conflict with any existing schedule
            if (
                // Case 1: New schedule starts before an existing schedule and ends during it
                ($start_time <= $schedule->start_time && $end_time >= $schedule->start_time && $end_time <= $schedule->end_time) ||

                // Case 2: New schedule starts during an existing schedule and ends after it
                ($start_time >= $schedule->start_time && $start_time <= $schedule->end_time && $end_time >= $schedule->end_time) ||

                // Case 3: New schedule starts and ends within an existing schedule
                ($start_time >= $schedule->start_time && $end_time <= $schedule->end_time) ||

                // Case 4: New schedule completely overlaps an existing schedule
                ($start_time <= $schedule->start_time && $end_time >= $schedule->end_time)
            ) {
                // Prevent creation due to conflicts
                return ['status' => 'conflict', 'schedule' => $schedule];
            }
        }

        // If no existing schedule is found, create a new one
        $created_schedule = DoctorSchedule::create([
            "doctor_id" => $doctor_id,
            "clinic_id" => $clinic_id,
            "day_of_week" => $day_of_week,
            "start_time" => $start_time,
            "end_time" => $end_time,
            "appointment_duration" => $appointment_duration,
        ]);

        return ['status' => 'created', 'schedule' => $created_schedule];
    }

    //Update DoctorSchedule
    public static function updateSchedule(DoctorSchedule $doctorschedule, Request $request)
    {
        $doctorscheduleData = $request->validated();

        // Update the doctor schedule's data
        $doctorschedule->update($doctorscheduleData);

        return $doctorschedule;
    }



    //Delete DoctorSchedule
    public static function deleteSchedule(DoctorSchedule $doctorschedule)
    {
        // Delete the doctor schedule
        $doctorschedule->delete();
    }


    // Appointment Section

    // Create Appointment
    public static function createAppointment($doctor_schedule_id, $patient_id, $appointment_date, $appointment_type, $appointment_status, $reason_for_visit)
    {
        // Retrieve the doctor schedule for the given $doctor_schedule_id
        $doctorSchedule = DoctorSchedule::find($doctor_schedule_id);


        // Check if there are any existing appointments for the same doctor_schedule_id, appointment_date, and specified statuses
        $existingAppointments = Appointment::where('doctor_schedule_id', $doctor_schedule_id)
            ->where('appointment_date', $appointment_date)
            ->whereIn('appointment_status', ['Scheduled', 'Completed'])
            ->get();

        foreach ($existingAppointments as $existingAppointment) {

            $existingTime = Carbon::parse($existingAppointment->appointment_date)->format('H:i:s');
            $inputTime = Carbon::parse($appointment_date)->format('H:i:s');

            if ($existingTime === $inputTime) {
                return ['status' => 'exists', 'appointment' => $existingAppointment];
            }
        }

        $carbonDate = Carbon::parse($appointment_date);
        $appointment_day = $carbonDate->format('l');

        // Check if the day of the appointment_date matches the doctor's schedule day_of_week
        if ($appointment_day !== $doctorSchedule->day_of_week) {
            // Handle the case where the appointment date's day doesn't match the doctor's schedule
            return ['status' => 'invalid_day', 'message' => 'Appointment day does not match the doctor\'s schedule.'];
        }

        // If no existing appointments are found, create a new appointment
        $createdAppointment = Appointment::create([
            "doctor_schedule_id" => $doctor_schedule_id,
            "patient_id" => $patient_id,
            "appointment_date" => $appointment_date,
            "appointment_type" => $appointment_type,
            "appointment_status" => $appointment_status,
            "reason_for_visit" => $reason_for_visit,
        ]);

        return ['status' => 'created', 'appointment' => $createdAppointment];
    }
    //Update Appointment
    public static function updateAppointment(Appointment $appointment, Request $request)
    {
        $appointmentData = $request->validated();

        // Update the Appointment data
        $appointment->update($appointmentData);

        return $appointment;
    }

    //Delete Appointment
    public static function deleteAppointment(Appointment $appointment)
    {
        // Delete the Appointment
        $appointment->delete();
    }

    // Medical Record Section

    // Create Medical Record
    public static function createMedicalRecord($appointment_id, $medical_condition, $diagnosis, $prescription, $follow_up_date, $additional_notes, $active)
    {
        // Check if a medical record with the same appointment ID already exists
        $existingMedicalRecord = MedicalRecord::where('appointment_id', $appointment_id)->first();

        if ($existingMedicalRecord) {
            // An existing record was found, return it with a status flag and message
            return ['status' => 'exists', 'record' => $existingMedicalRecord];
        }

        // Create the medical record if no existing record is found
        $createdMedicalRecord = MedicalRecord::create([

            'appointment_id' => $appointment_id,
            'medical_condition' => $medical_condition,
            'diagnosis' => $diagnosis,
            'prescription' => $prescription,
            'follow_up_date' => $follow_up_date,
            'additional_notes' => $additional_notes,
            'active' => $active,
        ]);

        return ['status' => 'created', 'record' => $createdMedicalRecord];
    }

    //Update Medical Record
    public static function updateMedicalRecord(MedicalRecord $medicalrecord, Request $request)
    {
        $medicalrecordData = $request->validated();

        // Update the Appointment data
        $medicalrecord->update($medicalrecordData);

        return $medicalrecord;
    }

    //Delete Medical Record
    public static function deleteMedicalRecord(MedicalRecord $medicalrecord)
    {
        // Delete the Appointment
        $medicalrecord->delete();
    }
}
