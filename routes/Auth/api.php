<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use Illuminate\Support\Facades\Route;

Route::post("/Register", [RegisterController::class, "Register"]);
Route::post("/Login", [LoginController::class, "Login"]);
