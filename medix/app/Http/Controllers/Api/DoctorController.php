<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\DoctorRequest;
use App\Http\Resources\DoctorResource;
use App\Models\Doctor;
use App\Models\User;
use Illuminate\Support\Facades\Gate;

class DoctorController extends Controller
{
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
    public function store(Doctor $doctor, DoctorRequest $request)
    {
        Gate::authorize("create", $doctor);
        $doctorDetails = $request->validated();
        $doctorDetails["user_id"] = request()->user()->id;

        if(User::where("user_id", $doctorDetails["user_id"])){
            return response()->json([
                "message"=>"Details already provided. Try updating instead",
                "status"=>"failed"
            ], 500);
        }

        $newDoctor = $doctor::create($doctorDetails);

        return $newDoctor;
    }

    /**
     * Display the specified resource.
     */
    public function show(Doctor $doctor)
    {
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

        $updateDoctor = $doctor->update($updateDetails);
        if($updateDoctor){
            return response()->json([
                "message"=>"You have updated your data successfully",
                "status"=>"success"
            ]);
        }else{
            return response()->json([
                "error"=>"Something went wrong update failed"
            ]);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Doctor $doctor)
    {
        Gate::authorize("destroy", $doctor);
        $doctor->delete();

        if($doctor){
            return response()->json([
                "message"=>"Data have been successfully deleted",
                "status"=>"success"
            ]);
        }else{
            return response()->json([
                "errpr"=>"Failed to update. Please try again later",
                "status"=>"failed"
            ]);
        }
    }
}
