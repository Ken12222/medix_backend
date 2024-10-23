<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\PatientReportRequest;
use App\Models\Patient;
use App\Models\PatientReport;
use Illuminate\Http\Request;

class PatientReportController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index($doctorID, $patientID)
    {
        $doctorPatient = Patient::where("id", $patientID)->first();
        
        $patientReports = PatientReport::where("doctor_id", $doctorPatient->doctor_id)
        ->where("patient_id", $doctorPatient->patient_id)->first();

        if(!$patientReports){
            return response()->json([
                "message"=>"there are no reports for this user",
                "status"=>"failed"
            ], 404);
            exit;
        }else{
            return response()->json([
                $patientReports,
                "status"=>"success"
            ]);
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(PatientReportRequest $request)
    {
        $reportData = $request->validated();

        $patientID = request()->patient;
        $doctorID = request()->doctor;

        $newReport = PatientReport::create([...$reportData, $patientID, $doctorID]);
        if(!$newReport){
            return response()->json([
                "message"=>"failed to create report. Please try again",
                "status"=>"failed"
            ]);
        }else{
            return response()->json([
                $newReport,
                "message"=>"Report added successfully",
                "status"=>"success"
            ]);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        return PatientReport::where("id", $id)->get();
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(PatientReportRequest $request, string $id)
    {
        $updateData = $request->validated();
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
