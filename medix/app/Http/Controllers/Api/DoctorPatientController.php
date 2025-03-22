<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\DoctorPatientRequest;
use App\Models\Doctor;
use App\Models\DoctorPatient;
use App\Models\Patient;
use Illuminate\Http\Request;

class DoctorPatientController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Doctor $doctor, Patient $patient)
    {
        
        if(request()->user()->role === "patient"){
            return DoctorPatient::where("patient_id", $patient->id)
            ->where("status", "approved")->with("doctor.user")->paginate();
        }else if(request()->user()->role === "doctor"){
            $newRequests = DoctorPatient::where("doctor_id", $doctor->id)
            ->where("status", "pending")->with("patient.user")->paginate();

            if(is_null($newRequests)){
                return response()->json([
                    "message"=>"there are no requests at the moment"
                ], 404);
            }else{
                return $newRequests;
            }
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(DoctorPatientRequest $request, Doctor $doctor, Patient $patient)
    {

        $addDocRequest = $request->validated();
        $addDocRequest["patient_id"] = request()->user()->patient->id;


        if($doctor === null || $patient === null){
            return response()->json([
                "message"=>"verify your account to Proceed.",
                "status"=>"failed"
            ], 500);
            exit;
        }elseif(!request()->user()){
            return response()->json([
                "message"=>"Your not authorize to access this route",
                "status"=>"failed"
            ], 500);
            exit;
        }

        if(DoctorPatient::where("doctor_id", $addDocRequest["doctor_id"])
        ->where("patient_id", $addDocRequest["patient_id"])
        ->first()){
            return response()->json([
                "message"=>"User is already added to your profile",
                "status"=>"failed"
            ], 500);
            exit;
        }

        $docRequestSent = DoctorPatient::create($addDocRequest);
        if($docRequestSent && request()->user()->role === "patient"){
            return response()->json([
                "message"=>"Request Sent. Waiting on doctors Approval",
                "status"=>"success"
            ], 200);
        }else{
            return response()->json([
                "message"=>"Failed to send request. Try agian later",
                "status"=>"failed"
            ], 422);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show( $doctorID,  $patientID)
    {
        $doctorPatientData = DoctorPatient::where([
        "doctor_id"=>$doctorID,
        "patient_id"=>$patientID
        ])
        ->with(["patient.user", "doctor.user"])
        ->first();

        if(!$doctorPatientData){
            return response()->json([
                "message"=>"No data found",
                "status"=>"failed"
            ], 404);
            exit;
        }
        
        if($patientID != $doctorPatientData->patient_id || $doctorID != $doctorPatientData->doctor_id){
            return response()->json([
                "message"=>"This user is not on your profile",
                "status"=>"failed"
            ], 422);
            exit;
        }

        return response()->json([
            "data"=> $doctorPatientData,
            "status"=>"success"
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request,  $doctorID, $patientID)
    {
        $requestApproval = $request->validate([
            "status"=> "required|string"
        ]);
        if(request()->user()->role !== "doctor"){
            return response()->json([
                "message"=>"only doctors can approve requests",
                "status"=>"failed"
            ], 422);
            exit;
        }

        $doctorPatient = DoctorPatient::where([
            ["doctor_id", $doctorID],
            ["patient_id", $patientID]
        ])->first();

        if($doctorPatient->status === "approved"){
            return response()->json([
                "message"=>"User is already added to your profile",
                "status"=>"failed"
            ], 422);
        }

        $doctorPatient->status = $requestApproval["status"];
        $approvedRequest = $doctorPatient->save();

        if(!$approvedRequest) {
            return response()->json([
                "message"=>"Approval failed. Please try again",
                "status"=>"failed"
            ], 422);  
        }

        return response()->json([
            "message"=>"Request Approved. User Added to Profile successfully",
            "status"=>"success"
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($doctorID, $patientID)
    {
        $doctorPatient = DoctorPatient::where("doctor_id", $doctorID)
        ->where("patient_id", $patientID)->with(["patient", "doctor"])->first();

        if(!$doctorPatient){
            return response()->json([
                "message"=>"No data found",
                "status"=>"failed"
            ], 404);
            exit;
        }

        if(request()->user()->role === "patient" && request()->user()->id != $doctorPatient->patient->user_id){
            return response()->json([
                "message"=>"You are not authorized",
                "status"=>"failed"
            ], 422);
            exit;
        }

        if(request()->user()->role === "doctor" && request()->user()->id != $doctorPatient->doctor->user_id){
            return response()->json([
                "message"=>"You are not authorized",
                "status"=>"failed"
            ], 422);
            exit;
        }





        $deleteUserFromProfile = $doctorPatient->delete();

        return response()->json([
            "message"=>"User successfully deleted from your profile",
            "status"=>"success"
        ]);
    }
}
