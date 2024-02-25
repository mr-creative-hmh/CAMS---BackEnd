<?php

namespace App\Http\Controllers;

use App\Models\Clinic\Category;
use App\Models\Clinic\Clinic;
use App\Models\Common\User;
use App\Models\Management\Appointment;
use App\Models\Management\DoctorSchedule;
use App\Models\Management\MedicalRecord;
use App\Models\User\ClinicManager;
use App\Models\User\Doctor;
use App\Models\User\Patient;
use App\Models\User\Specialization;
use App\Traits\ResponseMessage;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    use ResponseMessage;

    // Admin Dashboard
    public function adminDashboard()
    {
        $usersCount = User::count();
        $doctorsCount = Doctor::count();
        $clinicManagersCount = ClinicManager::count();
        $patientsCount = Patient::count();
        $categoriesCount = Category::count();
        $specializationsCount = Specialization::count();
        $appointmentsCount = Appointment::count();
        $medicalRecordsCount = MedicalRecord::count();

        return $this->SendResponse('Dashboard data retrieved successfully', [
            'usersCount' => $usersCount,
            'doctorsCount' => $doctorsCount,
            'clinicManagersCount' => $clinicManagersCount,
            'patientsCount' => $patientsCount,
            'categoriesCount' => $categoriesCount,
            'specializationsCount' => $specializationsCount,
            'appointmentsCount' => $appointmentsCount,
            'medicalRecordsCount' => $medicalRecordsCount
        ], 200);
    }

    // Clinic Manager Dashboard
    public function clinicManagerDashboard($clinicId)
    {
        if (!$clinicId) {
            return $this->SendResponse('Clinic ID is required', [], 400);
        }

        // Get clinic counts
        $clinic = Clinic::findOrFail($clinicId);
        $doctorsCount = $clinic->doctors()->count();
        $patientsCount = $clinic->patients()->count();

        // Get appointment counts
        $todayAppointmentsCount = $clinic->appointments()
            ->whereDate('appointment_date', today())
            ->count();

        $totalAppointmentsCount = $clinic->appointments()
            ->count();

        $completedAppointmentsCount = $clinic->appointments()
            ->where('appointment_status', 'Completed')
            ->count();
        $cancelledAppointmentsCount = $clinic->appointments()
            ->where('appointment_status', 'Cancelled')
            ->count();

        return $this->SendResponse('Dashboard data retrieved successfully', [
            'doctorsCount' => $doctorsCount,
            'patientsCount' => $patientsCount,
            'todayAppointmentsCount' => $todayAppointmentsCount,
            'totalAppointmentsCount' => $totalAppointmentsCount,
            'completedAppointmentsCount' => $completedAppointmentsCount,
            'cancelledAppointmentsCount' => $cancelledAppointmentsCount
        ], 200);
    }

    // Doctor Dashboard
    public function doctorDashboard($clinicId, $doctorId)
    {
        if (!$doctorId || !$clinicId) {
            return $this->SendResponse('Doctor ID and Clinic ID are required', [], 400);
        }

        // Find the doctor
        $doctor = Doctor::findOrFail($doctorId);

        // Get patient count for the doctor in the clinic
        $patientsCount = $doctor->patients()->count();

        // Get doctor's schedules for the clinic
        $doctorSchedules = DoctorSchedule::where('doctor_id', $doctorId)
            ->where('clinic_id', $clinicId)
            ->get();

        // Initialize counts
        $todayAppointmentsCount = 0;
        $scheduledAppointments = 0;
        $completedAppointmentsCount = 0;
        $cancelledAppointmentsCount = 0;

        foreach ($doctorSchedules as $schedule) {
            // Count today's appointments for the doctor's schedule in the clinic
            $todayAppointmentsCount += $schedule->appointments()
                ->whereDate('appointment_date', today())
                ->count();

            // Count scheduled appointments for the doctor's schedule in the clinic
            $scheduledAppointments += $schedule->appointments()
                ->where('appointment_status', 'Scheduled')
                ->count();

            // Count completed appointments for the doctor's schedule in the clinic
            $completedAppointmentsCount += $schedule->appointments()
                ->where('appointment_status', 'Completed')
                ->count();

            // Count cancelled appointments for the doctor's schedule in the clinic
            $cancelledAppointmentsCount += $schedule->appointments()
                ->where('appointment_status', 'Cancelled')
                ->count();
        }

        return $this->SendResponse('Dashboard data retrieved successfully', [
            'patientsCount' => $patientsCount,
            'todayAppointmentsCount' => $todayAppointmentsCount,
            'scheduledAppointments' => $scheduledAppointments,
            'completedAppointmentsCount' => $completedAppointmentsCount,
            'cancelledAppointmentsCount' => $cancelledAppointmentsCount
        ], 200);
    }
}
