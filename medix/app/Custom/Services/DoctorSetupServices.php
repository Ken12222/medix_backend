<?php

namespace App\Custom\Services;

use App\Models\Doctor;
use App\Http\Requests\DoctorRequest;
use App\Models\User;

Class DoctorSetupServices{

    public function kycComplete($id){
        $docDataExist = Doctor::where("id", $id)->first();

        if(!$docDataExist){
            return response()->json([
                "message"=>"Your data is not provided",
                "status"=>"failed"
            ], 403);
        }else{
            $doctor = User::where("id", $docDataExist->user_id)->first();
            if(!$doctor){
                return response()->json([
                    "message"=>"Account not found",
                    "status"=>"failed"
                ], 403); 
            }elseif($doctor && $doctor->KYC === true){
                return response()->json([
                    "message"=>"KYC is already completed",
                    "status"=>"failed"
                ], 403); 
            }else{
                $doctor->KYC = true;
                $doctor->save();
                return response()->json([
                    "message"=>"KYC completed successfully",
                    "status"=>"success"
                ], 200); 
            }
        }
    }
}