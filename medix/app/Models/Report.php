<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Report extends Model
{
    use HasFactory;

    protected $fillable = ["symptoms", "doc_report"];

    // public function Doctor(){
    //     return $this->belongsToMany(Doctor::class, "doctor_patient_report")
    //     ->withPivot("doctor_id");;
    // }

    // public function Patient(){
    //     return $this->belongsToMany(Patient::class, "doctor_patient_report")
    //     ->withPivot("patient_id");;
    // }

    public function doctorPatientReports()
    {
        return $this->hasMany(DoctorPatientReport::class, 'report_id');
    }

    public function doctors()
    {
        return $this->belongsToMany(Doctor::class, 'doctor_patient_report', 'report_id', 'doctor_id')
                    ->withPivot('patient_id') // Include the patient_id field from the pivot table
                    ->withTimestamps();
    }

    public function patients()
    {
        return $this->belongsToMany(Patient::class, 'doctor_patient_report', 'report_id', 'patient_id')
                    ->withPivot('doctor_id') // Include the doctor_id field from the pivot table
                    ->withTimestamps();
    }
}
