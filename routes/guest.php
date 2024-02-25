<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\GuestController;

// Route to get all clinic categories
Route::get('clinics-categories', [GuestController::class, 'getClinicsCategories']);

// Route to get clinics of a specific category
Route::get('clinics-categories/{categoryId}/clinics', [GuestController::class, 'getClinicsOfCategory']);

// Route to get doctors of a clinic
Route::get('clinics/{clinicId}/doctors', [GuestController::class, 'getDoctorsOfClinic']);

// Route to get schedules for doctor in clinic
Route::get('doctors/{doctorId}/clinics/{clinicId}/schedules', [GuestController::class, 'getSchedulesOfDoctorInClinic']);

Route::post('registerpatientuser', [GuestController::class, 'CreatePatientUser']);
