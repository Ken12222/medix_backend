<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DoctorPatientReport extends Model
{
    use HasFactory;

    protected $table = 'doctor_patient_report'; // Specify the table name

    protected $fillable = [
        'doctor_id',
        'patient_id',
        'report_id',
    ];

    public $incrementing = true; // Ensure the primary key is auto-incrementing

    // protected $fillable = ["patient_id", "doctor_id", "report_id"];

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
}
