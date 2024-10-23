<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\PatientRequest;
use App\Http\Resources\PatientResource;
use Illuminate\Http\Request;
use App\Models\Patient;
use App\Models\Doctor;
use Illuminate\Support\Facades\Gate;

class PatientController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index($doctorID)
    {   
        $authDoctor = Doctor::where("id", $doctorID)->first();
        if(request()->user()->id == $authDoctor->user_id){
            return PatientResource::collection(
                Patient::where("doctor_id", request()->user()->id)->with("user")
                ->paginate()
            );

        }else{
            return response()->json([
                "message"=>"no patients on your profile",
                "status"=>"failed"
            ], 403);

        }
        
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(PatientRequest $request, Patient $patient)
    {
        Gate::authorize("create", $patient);
        $patientDetails = $request->validated();
        $patientDetails["doctor_id"] = request()->user()->id;

        $authUser = Doctor::where("id", $patientDetails["doctor_id"])->first();
        if(!$authUser){
            return response()->json([
                "message"=>"complete your profile to access this page",
                "status"=>"failed"
            ], 403);
        }
        $checkPatientExists = Patient::where("doctor_id", $authUser->user_id)
        ->where("user_id", $patientDetails["user_id"])->first();
        if($checkPatientExists){
            return response()->json([
                "message"=>"user already added to your profile",
                "status"=>"failed"
            ]);
        }

        $patient = patient::create($patientDetails);

        if($patient){
            return response()->json([
                "message"=>"Patient Added successfully",
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
    public function show($doctorID, string $id)
    {
        //get patient data from db
        $patient = Patient::where("id", $id)
        ->where("doctor_id", $doctorID)
        ->first();

        Gate::authorize("view", $patient);

        if(empty($patient->user_id)){
            return response()->json([
                "message"=>"Patient not found please try adding them again",
                "status"=>"failed"
            ]);
        }else if(request()->user()->id !== $patient->doctor_id){
            return response()->json([
                "message"=>"Patient is not in your profile",
                "status"=>"failed"
            ]);
        }else{

        return new PatientResource(
            $patient->with(["patientReport", "user"])
            ->where("doctor_id", request()->user()->id)
            ->where("user_id", $patient->user_id)
            ->first()
        );

        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(PatientRequest $request)
    {
        $updateData = $request->validated();
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Doctor $doctor, string $id)
    {
        $patient = Patient::where("id", $id)->first();

        Gate::authorize("delete", $patient);

        $delPatient = $patient->delete();
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
