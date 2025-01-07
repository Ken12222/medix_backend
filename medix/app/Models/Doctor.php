<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Doctor extends Model
{
    use HasFactory;

    protected $fillable = ["user_id", "med_licence_number", "phone", "specialty"];

    public function User(){
        return $this->belongsTo(User::class);
    }

    // public function Patient(){
    //     return $this->belongsToMany(Patient::class, "doctor_patients");
    // }

    // public function Report(){
    //     return $this->belongsToMany(Report::class, "doctor_patient_report")
    //     ->withPivot("patient_id");
    //}

     public function patients()
    {
        return $this->belongsToMany(Patient::class, 'doctor_patient_report', 'doctor_id', 'patient_id')
                    ->withPivot('report_id') // Include the report_id field from the pivot table
                    ->withTimestamps(); // Include timestamps if present
    }
    

    public function reports()
    {
        return $this->hasManyThrough(
            Report::class,
            DoctorPatientReport::class,
            'doctor_id', // Foreign key on doctor_patient_report table
            'id',        // Foreign key on reports table
            'id',        // Local key on doctors table
            'report_id'  // Local key on doctor_patient_report table
        );
    }

    public function appointment(){
        return $this->hasManyThrough(Appointment::class, appointment_doctor_patient::class,
        'doctor_id', // Foreign key on appointment_doctor_patient table
        'id',        // Foreign key on reports table
        'id',        // Local key on doctors table
        'appointment_id'  // Local key on appointment_doctor_patient table
    );
    }

    public function patient()
    {
        return $this->belongsToMany(Patient::class, 'appointment_doctor_patients', 'doctor_id', 'patient_id')
                    ->withPivot('appointment_id') // Include the report_id field from the pivot table
                    ->withTimestamps(); // Include timestamps if present
    }
}
