<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\PatientRequest;
use App\Http\Resources\PatientResource;
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
        return PatientResource::collection(
            $doctor->patient()->with("user")
            ->whereHas("user", function($query){
                $query->where("role", "patient");
            })
            ->paginate()
        );
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(PatientRequest $request, Doctor $doctor)
    {
        $reportData = $request->validated();
        $reportData["doctor_id"] = 1;
        $reportData["patient_id"] =2;

        $patientReport = $doctor->patient()->create($reportData);

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
    public function show(Doctor $doctor, string $id)
    {
        return $doctor->patient->where("id", $id)->first();
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(PatientRequest $request, Doctor $doctor, string $id)
    {
        $updateData = $request->validated();
        $updatedReport = $doctor->patient()->update($updateData);

        if($updatedReport){
            return response()->json([
                "update Details"=> $updatedReport,
                "message"=>"Update successful",
                "status"=>"success"
            ], 200);
            exit;
        }else{
            return response()->json([
                "message"=>"failed to update",
                "status"=>"failed"
            ], 500);
            exit;
        }

    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Doctor $doctor, string $id)
    {
        $delPatient = $doctor->patient()->where("id", $id)->delete();
        if($delPatient){
            return response()->json([
                "message"=>"You have successfully deleted Patient",
                "status"=>"success"
            ], 200);
        }else{
            return response()->json([
                "message"=>"failed to delete. Please try again later",
                "status"=>"failed"
            ], 500);
        }

    }
}
