<?php

namespace App\Http\Controllers;

use App\Http\Resources\Clinics\CategoryResource;
use App\Http\Resources\Clinics\ClinicResource;
use App\Http\Resources\Management\AppointmentResource;
use App\Http\Resources\Management\DoctorScheduleResource;
use App\Http\Resources\Users\DoctorResource;
use App\Models\Clinic\Category;
use App\Models\Clinic\Clinic;
use App\Models\Management\Appointment;
use App\Models\Management\DoctorSchedule;
use App\Models\User\Patient;
use Carbon\Carbon;
use Illuminate\Http\Request;

class AppointmentHelperController extends Controller
{
    //Appointments Functions
    public function getClinics()
    {
        return ClinicResource::collection(Clinic::all());
    }

    // Method to get doctors of a clinic
    public function getDoctorsOfClinic($clinicId)
    {
        $clinic = Clinic::findOrFail($clinicId);
        $doctors = $clinic->doctors()->get();
        return DoctorResource::collection($doctors);
    }

    // Method to get schedules of a doctor in a clinic
    public function getSchedulesOfDoctorInClinic($doctorId, $clinicId)
    {
        $schedules = DoctorSchedule::where('doctor_id', $doctorId)
            ->where('clinic_id', $clinicId)
            ->get();

        return DoctorScheduleResource::collection($schedules);
    }

    public function generateDates($doctorScheduleId)
    {
        // Retrieve the doctor schedule by ID
        $doctorSchedule = DoctorSchedule::findOrFail($doctorScheduleId);

        // Get today's date
        $today = Carbon::today();

        // Array to store dates
        $dates = [];

        // Generate dates for the next 30 days
        for ($i = 0; $i < 30; $i++) {
            $date = $today->copy()->addDays($i);
            // Check if the current date matches the day_of_week of the doctorSchedule
            if ($date->englishDayOfWeek === $doctorSchedule->day_of_week) {
                $dates[] = $date->toDateString(); // Format as YYYY-MM-DD
            }
        }

        // Here you can associate the dates with the doctor_schedule_id
        // and save it to the database or perform any other required operations

        return response()->json([
            'doctor_schedule_id' => $doctorScheduleId,
            'dates' => $dates
        ]);
    }

    public function getSchedulesAndGenerateDates($clinicId, $doctorId)
    {
        // Retrieve schedules of a doctor in a clinic
        $schedules = DoctorSchedule::where('doctor_id', $doctorId)
            ->where('clinic_id', $clinicId)
            ->get();

        // Get today's date
        $today = today();

        // Array to store dates
        $generatedDates = [];

        // Iterate through each schedule
        foreach ($schedules as $schedule) {
            // Generate dates for the next 30 days
            for ($i = 0; $i < 15; $i++) {
                $date = $today->copy()->addDays($i);
                // Check if the current date matches the day_of_week of the schedule
                if ($date->englishDayOfWeek === $schedule->day_of_week) {
                    $generatedDates[] = [
                        'doctor_schedule_id' => $schedule->id,
                        'date' => $date->toDateString(), // Format as YYYY-MM-DD
                    ];
                }
            }
        }

        // Sort the generated dates by date
        usort($generatedDates, function ($a, $b) {
            return strtotime($a['date']) - strtotime($b['date']);
        });

        // Return the sorted dates as JSON
        return response()->json($generatedDates);
    }

    public function availableAppointments($doctorScheduleId, $date)
    {
        try {
            // Retrieve the doctor schedule
            $doctorSchedule = DoctorSchedule::findOrFail($doctorScheduleId);

            // Parse the date
            $selectedDate = Carbon::parse($date);

            // Extract start time, end time, and appointment duration from the doctor schedule
            $startTime = Carbon::parse($doctorSchedule->start_time);
            $endTime = Carbon::parse($doctorSchedule->end_time);
            $appointmentDuration = $doctorSchedule->appointment_duration; // Assuming in minutes

            $existingAppointments = Appointment::where('doctor_schedule_id', $doctorScheduleId)
                ->whereDate('appointment_date', $selectedDate)
                ->get();

            $availableAppointments = [];

            // Generate a list of available appointment times for the given date
            $currentTime = $startTime->copy();
            while ($currentTime->lte($endTime)) {
                // Format the appointment time
                $formattedTime = $selectedDate->copy()->setTime($currentTime->hour, $currentTime->minute)->format('Y-m-d H:i:s');

                // Check if the appointment time is available (i.e., not already booked)
                $isBooked = $existingAppointments->contains(function ($appointment) use ($formattedTime) {
                    // Check if the appointment date matches the formatted time
                    return $appointment->appointment_date == $formattedTime && $appointment->appointment_status == 'Scheduled';
                });
                if (!$isBooked) {
                    // Add the available appointment time to the list
                    $availableAppointments[] = [
                        'date' => $formattedTime, // Format date as string
                        'time' => $currentTime->format('h:i A') // Format time in 12-hour format
                    ];
                }
                // Move to the next time slot
                $currentTime->addMinutes($appointmentDuration);
            }

            // Return the list of available appointment times along with the doctor schedule ID
            return response()->json([
                'doctor_schedule_id' => $doctorScheduleId,
                'available_appointments' => $availableAppointments
            ]);
        } catch (\Exception $e) {
            // Handle any exceptions (e.g., invalid doctor_schedule_id or date format)
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    // Method to get all clinic categories
    public function getClinicsCategories()
    {
        $categories = Category::whereHas('clinics')->get();
        return CategoryResource::collection($categories);
    }

    // Method to get clinics of a specific category
    public function getClinicsOfCategory($categoryId)
    {
        $category = Category::findOrFail($categoryId);
        $clinics = $category->clinics()->get();
        return ClinicResource::collection($clinics);
    }

    public function getClinicAppointments($clinicId)
    {
        $clinic = Clinic::findOrFail($clinicId);
        $clinicAppointments = $clinic->appointments;
        return AppointmentResource::collection($clinicAppointments);
    }

    public function getPatientAppointments($patientId)
    {
        $patient = Patient::findOrFail($patientId);
        $patientAppointments = $patient->appointments;
        return AppointmentResource::collection($patientAppointments);
    }
}
