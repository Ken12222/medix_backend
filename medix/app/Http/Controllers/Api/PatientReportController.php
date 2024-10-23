<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\PatientReportRequest;
use App\Http\Resources\PatientReportResource;
use App\Models\Patient;
use App\Models\PatientReport;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class PatientReportController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index($doctorID, $patientID)
    {
        Gate::authorize("index", PatientReport::class);
        $doctorPatient = Patient::where("id", $patientID)->first();

        $patientReports = PatientReport::where("doctor_id", $doctorPatient->doctor_id)
        ->where("patient_id", $doctorPatient->user_id)->paginate();

        if(!$patientReports){
            return response()->json([
                "message"=>"there are no reports for this user",
                "status"=>"failed"
            ], 404);
            exit;
        }else{
            return PatientReportResource::collection(
                $patientReports
            );
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(PatientReportRequest $request)
    {
        Gate::authorize("create", PatientReport::class);
        $reportData = $request->validated();

        $doctorPatient = Patient::where("id", request()->patient)->first();

        $reportData["patient_id"] = $doctorPatient->user_id;
        $reportData["doctor_id"] = $doctorPatient->doctor_id;
        
        $newReport = PatientReport::create($reportData);
        if(!$newReport){
            return response()->json([
                "message"=>"failed to create report. Please try again",
                "status"=>"failed"
            ]);
        }else{
            return response()->json([
                "message"=>"Report added successfully",
                "status"=>"success"
            ]);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show($doctorID, $patientID, PatientReport $patient_report)
    {
        Gate::authorize("show", $patient_report);
        return new PatientReportResource(
            $patient_report
        );
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(PatientReportRequest $request, $doctorID, $patientID, PatientReport $patient_report)
    {
        Gate::authorize("update", $patient_report);
        $updateData = $request->validated();

        
        if(!$patient_report){
            return response()->json([
                "message"=>"failed to update report",
                "status"=>"failed"
            ], 403);
        }elseif(empty($patient_report)){
            return response()->json([
                "message"=>"failed to no report found",
                "status"=>"failed"
            ], 404);
        }else{
            $updatedData = $patient_report->update($updateData);
            return response()->json([
                "message"=>"You have successfully updated report",
                "status"=>"success"
            ], 200);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy( $doctorID, $patientID, PatientReport $patient_report)
    {
        Gate::authorize("delete", $patient_report);
        $reportDel = $patient_report->delete();
        if(!$reportDel){
            return response()->json([
                "message"=>"failed to delete report",
                "status"=>"failed"
            ], 403);
        }else{
            return response()->json([
                "message"=>"You have successfully deleted report",
                "status"=>"success"
            ], 200);
        }
    }
}
