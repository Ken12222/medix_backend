<?php

namespace App\Http\Controllers\Api;

use App\Custom\Services\AccountVerificationServices;
use App\Http\Controllers\Controller;
use App\Http\Requests\PatientRequest;
use App\Http\Resources\PatientResource;
use Illuminate\Http\Request;
use App\Models\Patient;
use Illuminate\Support\Facades\Gate;

class PatientController extends Controller
{
    public function __construct(private AccountVerificationServices $service){

    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return PatientResource::collection(
            Patient::with("user")->paginate()
        );
        
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(PatientRequest $request, Patient $patient)
    {
        Gate::authorize("create", $patient);
        $patientDetails = $request->validated();
        $patientDetails["user_id"] = request()->user()->id;

        $detailsExist = Patient::where("user_id", $patientDetails["user_id"])->first();
        if($detailsExist){
            return response()->json([
                "message"=>"Profile is already verified",
                "status"=>"failed"
            ], 422);
        }

        if(request()->user()->role === "patient"){

        $patientDataAdded = Patient::create($patientDetails);
        $verifyPatient = $this->service->kycPatientComplete($patientDataAdded->id);
        if($patientDataAdded && !$verifyPatient){
            $patientDataAdded->delete();
            return response()->json([
                "message"=>"failed to verify Profile",
                "status"=>"failed"
            ], 422); 
        }else{
            return $verifyPatient;
        }
        }else{
            return response()->json([
                "message"=>"you are not allowed to take this action",
                "status"=>"failed"
            ], 422); 
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Patient $patient)
    {
        Gate::authorize("view", $patient);

        return new PatientResource(
            $patient->with(["PatientReport", "user"])->first()
        );

    }

    /**
     * Update the specified resource in storage.
     */
    public function update(PatientRequest $request, Patient $patient)
    {
        Gate::authorize("update", $patient);
        $patientDetails = $request->validated();

        if(request()->user()->role === "patient"){
            $updateData = $patient->update($patientDetails);
            if(!$updateData){
                return response()->json([
                    "message"=>"failed to update Profile data",
                    "status"=>"failed"
                ], 422); 
            }else{
                return response()->json([
                    "message"=>"Profile updated successfully",
                    "status"=>"success"
                ], 422); 
            }
        }else{
            return response()->json([
                "message"=>"You are not allowed update this data",
                "status"=>"failed"
            ], 422); 
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Patient $patient)
    {
        Gate::authorize("delete", $patient);

        if(request()->user()->role === "patient"){
            $user = request()->user();
            $patient->delete();
            $user->KYC = null;
            $user->save();
            if(!$patient){
                return response()->json([
                    "message"=>"failed to delete Profile data",
                    "status"=>"failed"
                ], 422); 
            }else{
                return response()->json([
                    "message"=>"Profile data deleted successfully",
                    "status"=>"success"
                ], 200);
                
            }
        }else{
            return response()->json([
                "message"=>"You are not allowed update this data",
                "status"=>"failed"
            ], 200);
        }
    }
}
