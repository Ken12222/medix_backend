<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Doctor;
use App\Models\Doctor_patient;
use App\Models\Patient;
use Illuminate\Http\Request;

class DoctorPatientController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Doctor $doctor, Patient $patient)
    {
        return Doctor_patient::where("doctor_id", $doctor->user_id)
        ->orWhere("patient_id", $patient->user_id)
        ->with(["Patient", "Doctor"])
        ->paginate();
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
