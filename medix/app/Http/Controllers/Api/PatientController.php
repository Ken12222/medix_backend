<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\PatientRequest;
use Illuminate\Http\Request;
use App\Models\Patient;
use App\Models\Doctor;

class PatientController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Doctor $doctor)
    {
        return $doctor->patient->all();
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(PatientRequest $request)
    {
        $reportData = $request->validated();
        $reportData["user_id"] = 2;
        $reportData["patient_id"] =1;

        $patientReport = Patient::create($reportData);

        if($patientReport){
            return response()->json([
                "Report"=>$patientReport,
                "message"=>"Report has been submitted successfully",
                "status"=>"success"
            ], 200);
        }else{
            return response()->json([
                "message"=>"failed to create report. Try again later",
                "status"=>"failed"
            ], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Patient $patient)
    {
        return $patient;
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(PatientRequest $request, Patient $patient)
    {
        $updateData = $request->validated();
        $updatedReport = $patient->update($updateData);

        if($updatedReport){
            return response()->json([
                "update Details"=> $updatedReport,
                "message"=>"Update successful",
                "status"=>"success"
            ], 200);
        }else{
            return response()->json([
                "message"=>"failed to update",
                "status"=>"failed"
            ], 500);
        }

    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Patient $paitent)
    {
        $paitent->delete();
    }
}
