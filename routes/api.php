<?php

use App\Http\Controllers\AppointmentHelperController;
use App\Http\Controllers\Clinic\CategoryController;
use App\Http\Controllers\Clinic\ClinicController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\Management\AppointmentController;
use App\Http\Controllers\Management\DoctorScheduleController;
use App\Http\Controllers\Management\MedicalRecordController;
use App\Http\Controllers\Pivot\ClinicDoctorController;
use App\Http\Controllers\Pivot\ClinicPatientController;
use App\Http\Controllers\Pivot\DoctorPatientController;
use App\Http\Controllers\User\Role\ClinicManagerController;
use App\Http\Controllers\User\Role\DoctorController;
use App\Http\Controllers\User\Role\PatientController;
use App\Http\Controllers\User\Role\SpecializationController;
use App\Http\Controllers\User\RoleController;
use App\Http\Controllers\User\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return "tesing";
// });


Route::apiResource("user", UserController::class);
Route::apiResource("doctor", DoctorController::class);
Route::apiResource("patient", PatientController::class);
Route::apiResource("clinicmanager", ClinicManagerController::class);
Route::apiResource("role", RoleController::class);
Route::apiResource("specialization", SpecializationController::class);
Route::apiResource("category", CategoryController::class);
Route::apiResource("clinic", ClinicController::class);
Route::apiResource("doctorschedule", DoctorScheduleController::class);
Route::apiResource("appointment", AppointmentController::class);
Route::apiResource("medicalrecord", MedicalRecordController::class);
Route::get("appointment/{id}/available-appointments/{date}", [AppointmentController::class, "getAvailableAppointments"]);

// Route to change password with current password check
Route::post('/user/{user}/changepassword', [UserController::class, 'changePassword']);

// Route to change password without current password check
Route::post('/user/{user}/changepasswordadmin', [UserController::class, 'changePasswordAdmin']);

Route::get('/clinicdoctors/{clinicId}', [ClinicDoctorController::class, 'clinicDoctors']);
Route::get('/doctorclinics/{doctorId}', [ClinicDoctorController::class, 'doctorClinics']);
Route::get('/unassigneddoctors/{clinicId}', [ClinicDoctorController::class, 'unassignedDoctors']);
Route::post('/clinicdoctors/assign/{doctorId}/{clinicId}', [ClinicDoctorController::class, 'assignDoctor']);
Route::delete('/clinicdoctors/unassign/{doctorId}/{clinicId}', [ClinicDoctorController::class, 'unAssignDoctor']);


Route::get('/clinicpatients/{clinicId}', [ClinicPatientController::class, 'clinicPatients']);
Route::get('/unassignedpatients/{clinicId}', [ClinicPatientController::class, 'unassignedPatients']);
Route::post('/clinicpatients/assign/{patientId}/{clinicId}', [ClinicPatientController::class, 'assignPatient']);
Route::delete('/clinicpatients/unassign/{patientId}/{clinicId}', [ClinicPatientController::class, 'unAssignPatient']);

Route::get('/appointment/clinic/{clinicId}', [AppointmentHelperController::class, 'getClinics']);
Route::get('/appointment/clinicdoctors/{clinicId}', [AppointmentHelperController::class, 'getDoctorsOfClinic']);
Route::get('/appointment/doctorschedules/{clinicId}/{doctorId}', [AppointmentHelperController::class, 'getSchedulesOfDoctorInClinic']);
Route::get('/available-appointments/{doctorScheduleId}/{date}', [AppointmentHelperController::class, 'availableAppointments']);
Route::get('/generate-dates/{doctorScheduleId}', [AppointmentHelperController::class, 'generateDates']);
Route::get('/doctorschedulesdates/{clinicId}/{doctorId}', [AppointmentHelperController::class, 'getSchedulesAndGenerateDates']);
Route::get('/clinic/appointments/{clinicId}', [AppointmentHelperController::class, 'getClinicAppointments']);
Route::get('/patient/appointments/{patientId}', [AppointmentHelperController::class, 'getPatientAppointments']);

Route::get('/users/{userId}/additional-info', [UserController::class, 'getAdditionalInfo']);

Route::get('/doctorpatients/{doctorId}', [DoctorPatientController::class, 'doctorPatients']);
Route::get('/medicalrecords/{patientId}', [MedicalRecordController::class, 'patientMedicalRecords']);
Route::get('/medicalrecord/appointment/{appointmentId}', [MedicalRecordController::class, 'appointmentMedicalRecord']);

Route::get('/doctorsschedules/{clinicId}', [DoctorScheduleController::class, 'clinicDoctorSchedules']);

//Dashboards Data:

Route::get('/dashboards/admin/', [DashboardController::class, 'adminDashboard']);
Route::get('/dashboards/clinicmanager/{clinicId}', [DashboardController::class, 'clinicManagerDashboard']);
Route::get('/dashboards/doctor/{clinicId}/{doctorId}', [DashboardController::class, 'doctorDashboard']);

//Route::group(['auth:sanctum' => 'role:'], function () { });
