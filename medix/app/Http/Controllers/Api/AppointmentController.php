<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Http\Requests\AppointmentRequest;
use App\Http\Resources\AppointmentResource;
use App\Models\Appointment;
use App\Models\Doctor;
use App\Models\DoctorPatient;
use App\Models\Patient;
use Illuminate\Http\Request;
use Exception;
use Illuminate\Support\Facades\Auth;

class AppointmentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Doctor $doctor)
    {
        //$authUserID = request()->user()->id;
        //$appointmentID =  appointment_doctor_patient::where("patient_id", $authUserID)->orWhere("doctor_id", $authUserID)->get();
        if(Auth::user()->role === "doctor"){

            return AppointmentResource::collection(
                Appointment::with("patient.user")->where("doctor_id", request()->user()->doctor->id)->get()
            ) ;
        }else{
            return AppointmentResource::collection(
               Appointment::with("doctor")->where("patient_id", request()->user()->patient->id)->get()
            );
        }
        
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(AppointmentRequest $request)
    {
        $appointmentData = $request->validated();

        //check if doctor is already added to pstient profile be kooing appointment
        $isDoctorOnMyProfile= DoctorPatient::where("doctor_id", $appointmentData["doctor_id"])
        ->where("status", "approved")->first();
        if($isDoctorOnMyProfile){ 
            Appointment::create($appointmentData);
        }else{
            return response()->json(["message"=>"add doctor to your profile to book appointment"], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Doctor $doctor, Patient $patient, Appointment $appointment)
    {

        if(Auth::user()->role === "patient"){

            return AppointmentResource::collection(
                $appointment->with("doctor.user")->where("id", $appointment->id)->get()
            );

        }
        if(Auth::user()->role ===  "doctor"){
            return AppointmentResource::collection(
                Auth::user()->doctor->appointment
            );
        }
        
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $doctorID,  Appointment $appointment)
    {
        $updateData= $request->validate([
            "appointment_date"=>"required|date",
            "appointment_time"=>"required|date_format:H:i:s",
            "reason"=>"required|string|max:255"
        ]);

        $update =  $appointment->update($updateData);
        if($update){

        return response()->json([
            "message"=>"Appointment updated successfully",
            "status"=>"success"
        ],200);}
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($doctorID, Appointment $appointment)
    {
        $delete = $appointment->delete();
        if($delete){
            return response()->json([
                "message"=>"Appointment deleted successfully",
                "status"=>"success"
            ]);
        }else{
            return response()->json([
                "error"=>"failed to delete Appointment"
            ], 422);
        }
    }
}
