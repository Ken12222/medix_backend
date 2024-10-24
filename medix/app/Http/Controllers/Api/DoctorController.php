<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\DoctorRequest;
use App\Http\Resources\DoctorResource;
use App\Models\Doctor;
use App\Models\User;
use Illuminate\Support\Facades\Gate;
use App\Custom\Services\AccountVerificationServices;

class DoctorController extends Controller
{

    public function __construct(private AccountVerificationServices $service){

    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return DoctorResource::collection(
            Doctor::with("user")
                ->whereHas("user", function($query){
                $query->where("role", "doctor");
            })
            ->paginate()
        );
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(DoctorRequest $request)
    {
        Gate::authorize("create", Doctor::class);

        $doctorDetails = $request->validated();
        $doctorDetails["user_id"] = request()->user()->id;

        $doctorExist = Doctor::where("user_id", $doctorDetails["user_id"])->first();

        if($doctorExist){
            return response()->json([
                "message"=>"Details already provided. Try updating instead",
                "status"=>"failed"
            ], 422);
           exit();
        }
        if(request()->user()->role === "doctor"){
        $newDoctor = Doctor::create($doctorDetails);

        $verifyDoctor = $this->service->kycComplete($newDoctor->id);
        return $verifyDoctor;
        }else{
            return response()->json([
                "message"=>"You are not allowed to take this action",
                "status"=>"failed"
            ], 422);
           exit();
        }
        
    }

    /**
     * Display the specified resource.
     */
    public function show(Doctor $doctor)
    {
        Gate::authorize("view", $doctor);
        return new DoctorResource (
            $doctor::with("user")->first()
        );
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(DoctorRequest $request, Doctor $doctor)
    {
        Gate::authorize("update", $doctor);

        $updateDetails = $request->validated();
        
        if(!$doctor){
            return response()->json([
                "message"=>"details not found. please consider adding your details instead",
                "status"=>"failed"
            ]);
        }

        if(request()->user()->role === "doctor"){

            $updateDoctor = $doctor->update($updateDetails);
            if($updateDoctor){
                return response()->json([
                    "message"=>"You have updated your data successfully",
                    "status"=>"success"
                ]);
            }else{
                return response()->json([
                    "message"=>"Something went wrong update failed"
                ]);
            }
        }else{
            return response()->json([
                "message"=>"You are not allowed to update this"
            ]);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Doctor $doctor)
    {
        Gate::authorize("delete", $doctor); 

        if(request()->user()->role === "doctor"){

            $user = request()->user();
            $doctor->delete();
            $user->KYC = null;
            $user->save();
            if(!$doctor){
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
            ], 422);
        }
    }
}
