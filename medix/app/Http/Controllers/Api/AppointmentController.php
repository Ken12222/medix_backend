<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Http\Requests\AppointmentDoctorPatientRequest;
use App\Http\Requests\AppointmentRequest;
use App\Models\Appointment;
use App\Models\appointment_doctor_patient;
use App\Models\Doctor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Exception;

class AppointmentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Doctor $doctor)
    {
        return $doctor->appointment;
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
    public function store(AppointmentRequest $request, AppointmentDoctorPatientRequest $appointRequest)
    {
        $appointmentData = $request->validated();

        $appointmentDoctorPatient = $appointRequest->validated();

        $newAppointment = DB::beginTransaction();
            
        try{
            $saveAppointment = Appointment::create($appointmentData);
            $appointmentDoctorPatient["appointment_id"] = $saveAppointment->id;

            $saveappointmentDoctorPatient = appointment_doctor_patient::create($appointmentDoctorPatient);
            DB::commit();
            return response()->json([
                "message"=>"Appointment created successfully",
                "status"=>"success"
            ], 200);

        }catch(Exception $e){

            return $e;
            DB::rollBack();
            return response()->json([
                "error"=>"Failed create appointment",
                "status"=>"faileds"
            ], 422);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Doctor $doctor, Appointment $appointment)
    {
        return $appointment;
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
