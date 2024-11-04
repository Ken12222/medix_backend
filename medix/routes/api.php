<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\DoctorController;
use App\Http\Controllers\Api\DoctorPatientController;
use App\Http\Controllers\Api\PatientController;
use App\Http\Controllers\Api\PatientReportController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

//Auth Routes
Route::post("register", [AuthController::class, "register"]);
Route::post("login", [AuthController::class, "login"]);
Route::post("logout", [AuthController::class, "logout"])->middleware(["auth:sanctum"]);
Route::post("resend_verify_link", [AuthController::class, "resendVerifyLink"])->middleware(["auth:sanctum"]);
Route::post("verify_email", [AuthController::class, "verifyEmail"]);


//Doctor routes
Route::get("doctor", [DoctorController::class, "index"]);
Route::get("doctor/{doctor}", [DoctorController::class, "show"]);
//authenticated doctor routes
Route::middleware(['auth:sanctum'])->group(function () {
    Route::post("doctor", [DoctorController::class, "store"]);
    Route::put("doctor/{doctor}", [DoctorController::class, "update"]);
    Route::delete("doctor/{doctor}", [DoctorController::class, "destroy"]);
});

//Doctor = My Patient
Route::middleware(['auth:sanctum'])->group(function () {
    Route::get("doctor/{doctor}/patient", [DoctorPatientController::class, "index"])->scopeBindings();
    Route::get("doctor/{doctor}/patient/{patient}", [DoctorPatientController::class, "show"])->scopeBindings();
    Route::post("doctor/{doctor}/patient", [DoctorPatientController::class, "store"])->scopeBindings();
    Route::post("doctor/{doctor}/patient/{patient}", [DoctorPatientController::class, "update"])->scopeBindings();
    Route::delete("doctor/{doctor}/patient/{patient}", [DoctorPatientController::class, "destroy"])->scopeBindings() ;
});

//Patient = My Doctor
Route::middleware(['auth:sanctum'])->group(function () {
    Route::get("patient/{patient}/doctor", [DoctorPatientController::class, "index"])->scopeBindings();
    Route::get("patient/{patient}/doctor/{doctor}", [DoctorPatientController::class, "show"])->scopeBindings();
    Route::post("patient/{patient}/doctor", [DoctorPatientController::class, "store"])->scopeBindings();
    Route::delete("patient/{patient}/doctor/{doctor}", [DoctorPatientController::class, "destroy"])->scopeBindings() ;
});

//Patient
Route::middleware(['auth:sanctum'])->group(function () {
    Route::get("patient", [PatientController::class, "index"]);
    Route::get("patient/{patient}", [PatientController::class, "show"]);
    Route::put("patient/{patient}", [PatientController::class, "update"]);
    Route::post("patient", [PatientController::class, "store"]);
    Route::delete("patient/{patient}", [PatientController::class, "destroy"]) ;
});

//Patient Report
Route::middleware(["auth:sanctum"])->group(function(){
    Route::get("doctor/{doctor}/patient/{patient}/patient_report", [PatientReportController::class, "index"])->scopeBindings();
    Route::get("doctor/{doctor}/patient/{patient}/patient_report/{patient_report}", [PatientReportController::class, "show"])->scopeBindings();
    Route::post("doctor/{doctor}/patient/{patient}/patient_report/{patient_report}", [PatientReportController::class, "update"])->scopeBindings();
    Route::delete("doctor/{doctor}/patient/{patient}/patient_report/{patient_report}", [PatientReportController::class, "destroy"])->scopeBindings();
    Route::post("doctor/{doctor}/patient/{patient}/patient_report", [PatientReportController::class, "store"])->scopeBindings();
});

