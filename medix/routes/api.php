<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\DoctorController;
use App\Http\Controllers\Api\PatientController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

//Auth Routes
Route::post("register", [AuthController::class, "register"]);
Route::post("login", [AuthController::class, "login"]);
Route::post("logout", [AuthController::class, "logout"])->middleware(["auth:sanctum"]);

//Doctor routes
Route::get("doctor", [DoctorController::class, "index"]);
Route::get("doctor/{doctor}", [DoctorController::class, "show"]);
//authenticated doctor routes
Route::middleware(['auth:sanctum'])->group(function () {
    Route::post("doctor", [DoctorController::class, "store"]);
    Route::put("doctor/{doctor}", [DoctorController::class, "update"]);
    Route::delete("doctor/{doctor}", [DoctorController::class, "destroy"]);
});

//Patient
Route::get("patient", [PatientController::class, "index"]);
Route::get("patient/{patient}", [PatientController::class, "show"]);
Route::put("patient/{patient}", [PatientController::class, "update"]);
Route::post("patient", [PatientController::class, "store"]);