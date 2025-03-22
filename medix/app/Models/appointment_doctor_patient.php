<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Appointment_Doctor_Patient extends Model
{
    use HasFactory;

    protected $fillable = [
        "doctor_id",
        "patient_id",
        "appointment_id"
    ];

    // public function Appointments(){
    //     return $this->hasManyThrough(Appointment::class, Appointment_Doctor_Patient::class,
    //     ""
    //     )
    // }
}
