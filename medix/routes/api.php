<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\DoctorController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');


//Doctor routes
Route::get("doctor", [DoctorController::class, "index"]);
Route::get("doctor/{doctor}", [DoctorController::class, "show"]);
//authenticated doctor routes
Route::middleware(['auth:sanctum'])->group(function () {
    Route::post("doctor", [DoctorController::class, "store"]);
    Route::put("doctor/{doctor}", [DoctorController::class, "update"]);
    Route::delete("doctor/{doctor}", [DoctorController::class, "destroy"]);
});