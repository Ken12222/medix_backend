<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\ReportRequest;
use App\Models\Doctor;
use App\Models\DoctorPatientReport;
use App\Models\DoctorPatient;
use App\Models\Patient;
use App\Models\Report;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Exception;

class ReportController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index($doctorID, $patientID, Report $report)
    {   
        $reports = DoctorPatientReport::where("doctor_id", $doctorID)
        ->where("patient_id", $patientID)->with("reports")->paginate();

        Gate::authorize("ViewAny", $report);
        return response()->json([
            "data"=>$reports
        ]);
    }


    /**
     * Store a newly created resource in storage.
     */
    public function store(ReportRequest $request, $doctorID, $patientID, Report $report)
    {   
        Gate::authorize("create", $report);
        
        $reportData = $request->validated();

        DB::beginTransaction();

        try {
            
            if(request()->user()->KYC === null){
                return response()->json([
                    "Verify your account to create reports",
                    "status"=>"failed"
                ], 422);
            }

            $patientExistsOnProfile = DoctorPatient::where([
                ["doctor_id", $doctorID],
                ["patient_id", $patientID],
            ])->first();

            if(!$patientExistsOnProfile){
                return response()->json([
                    "You can only create reports for users added to your profile",
                    "status"=>"failed"
                ], 422);
            }

            $newReport = Report::create($reportData);
            $newDoctorPatient = DoctorPatientReport::create([
                "patient_id"=>$reportData["patient_id"],
                "doctor_id"=>$doctorID,
                "report_id"=>$newReport->id
            ]);
    
            // Commit the report if everything is successful
            DB::commit();
    
            return response()->json([
                "message"=>"Report created successfully",
                "status"=>"success"
            ], 200);
    
        } catch (Exception $e) {
            // Rollback the report in case of an error
            DB::rollBack();
    
            // Return the error message
            return response()->json(['error' => $e->getMessage()], 400);
        }

        //inserting data to doctorpatientreport is not working

    }

    /**
     * Display the specified resource.
     */
    public function show($doctorID, $patientID, Report $report)
    {
        $reportDetails = DoctorPatientReport::where([
            ["doctor_id",$doctorID],
            ["patient_id", $patientID]])->where("report_id", $report)
        ->with("reports")->get();
    
        Gate::authorize("show", $report);

        if($reportDetails->isEmpty()){
            return response()->json([
                "message"=>"Report not found",
                "status"=>"failed"
            ], 201);
        }
        return response()->json([
            "report_details"=>$reportDetails,
            "status"=>"success"
        ]);

    }

    /**
     * Update the specified resource in storage.
     */
    public function update(ReportRequest $request, $doctorID, $patientID, Report $report)
    {
        Gate::authorize("update", $report);
        $updateReportData = $request->validated();
        $doctor = Doctor::find($doctorID);
        if(request()->user()->id !== $doctor->user_id){
            return response()->json([
                "message"=>"You are not allowed to update this report",
                "status"=>"failed"
            ], 422);
        }

        $updatedReport = $report->update($updateReportData);

        return response()->json([
            "message"=>"Data has been updated successfully",
            "status"=>"success"
        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($doctorID, $patientID, Report $report)
    {
        Gate::authorize("delete", $report);
        $report->delete();
        return response()->json([
            "message"=>"Report deleted successfully",
            "status"=>"success"
        ], );
    }
}
