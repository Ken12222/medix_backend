<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Patient extends Model
{
    use HasFactory;

    protected $fillable = [
        "user_id", "contact", 
        "insurance_card", "insurance_card_id",
        "current_medication", "emergency_contact"
    ];

    public function User(){
        return $this->belongsTo(User::class);
    }

    
    public function doctors()
    {
        return $this->belongsToMany(Doctor::class, 'doctor_patient_report', 'patient_id', 'doctor_id')
                    ->withPivot('report_id') // Include the report_id field from the pivot table
                    ->withTimestamps(); // Include timestamps if present
    }

    public function reports()
    {
        return $this->hasMany(Report::class);
        // return $this->hasManyThrough(
        //     Report::class,
        //     DoctorPatientReport::class,
        //     'patient_id', // Foreign key on doctor_patient_report table
        //     'id',         // Foreign key on reports table
        //     'id',         // Local key on patients table
        //     'report_id'   // Local key on doctor_patient_report table
        // );
    }

    public function appointment(){
        return $this->hasMany(Appointment::class
    );
    }

    // public function doctor(){
    //     return $this->belongsToMany(Doctor::class, 'appointment_doctor_patients', 'doctor_id', 'patient_id')
    //     ->withPivot('appointment_id')
    //     ->withTimestamps();
    // }

    // public function Report(){
    //     return $this->belongsToMany(Report::class, "doctor_patient_report")
    //     ->withPivot("doctor_id");
    // }
}
