<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\DoctorController;
use App\Http\Controllers\Api\DoctorPatientController;
use App\Http\Controllers\Api\PatientController;
use App\Http\Controllers\Api\ReportController;
use App\Http\Controllers\Api\AppointmentController;
use App\Http\Controllers\Api\UserController;

Route::get('/user', [UserController::class, "index"], function (Request $request) {
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
    Route::put("doctor/{doctor}/patient/{patient}", [DoctorPatientController::class, "update"])->scopeBindings();
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

//Report =
Route::middleware(["auth:sanctum"])->group(function(){
    Route::get("doctor/{doctor}/patient/{patient}/report", [ReportController::class, "index"])->scopeBindings();
    Route::get("doctor/{doctor}/patient/{patient}/report/{report}", [ReportController::class, "show"])->scopeBindings();
    Route::post("doctor/{doctor}/patient/{patient}/report/{report}", [ReportController::class, "update"])->scopeBindings();
    Route::delete("doctor/{doctor}/patient/{patient}/report/{report}", [ReportController::class, "destroy"])->scopeBindings();
    Route::post("doctor/{doctor}/patient/{patient}/report", [ReportController::class, "store"])->scopeBindings();
});

//Doctor appointment =
Route::middleware(["auth:sanctum"])->group(function(){
Route::get("doctor/{doctor}/appointment", [AppointmentController::class, "index"]);
Route::get("doctor/{doctor}/appointment/{appointment}", [AppointmentController::class, "show"]);
Route::put("doctor/{doctor}/appointment/{appointment}", [AppointmentController::class, "update"]);
Route::post("doctor/{doctor}/appointment", [AppointmentController::class, "store"]);
Route::delete("doctor/{doctor}/appointment/{appointment}", [AppointmentController::class, "destroy"]) ;
});
Route::get('/test', function () {
    return response()->json(['message' => 'API working']);
});


//patient AppointmentBooking
Route::middleware(["auth:sanctum"])->group(function(){
    Route::get("patient/{patient}/appointment", [AppointmentController::class, "index"]);
    Route::get("patient/{patient}/appointment/{appointment}", [AppointmentController::class, "show"]);
    Route::put("patient/{patient}/appointment/{appointment}", [AppointmentController::class, "update"]);
    Route::post("patient/{patient}/appointment", [AppointmentController::class, "store"]);
    Route::delete("patient/{patient}/appointment/{appointment}", [AppointmentController::class, "destroy"]) ;
    });


    //search doctor 
    
    // Route::get("/user/search?query=".$name, [DoctorController::class, "index"], function(Request $request){
    //     $name = $request->input("name");
    // });