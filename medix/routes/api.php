<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\DoctorController;
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

//Patient
Route::middleware(['auth:sanctum'])->group(function () {
    Route::get("doctor/{doctor}/patient", [PatientController::class, "index"])->scopeBindings();
    Route::get("doctor/{doctor}/patient/{patient}", [PatientController::class, "show"])->scopeBindings();
    Route::post("doctor/{doctor}/patient", [PatientController::class, "store"])->scopeBindings();
    Route::delete("doctor/{doctor}/patient/{patient}", [PatientController::class, "destroy"])->scopeBindings() ;
});

//Patient Report
Route::middleware(["auth:sanctum"])->group(function(){
    Route::get("doctor/{doctor}/patient/{patient}/patient_report", [PatientReportController::class, "index"])->scopeBindings();
    Route::post("doctor/{doctor}/patient/{patient}/patient_report", [PatientReportController::class, "index"])->scopeBindings();
});



//Patient Report
//Route::get("patient_report", [PatientReportController::class, "show"]);