<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class appointment_doctor_patient extends Model
{
    use HasFactory;

    protected $fillable = [
        "doctor_id",
        "patient_id",
        "appointment_id"
    ];

    public function appointment(){
        return $this->hasManyThrough(Appointment::class, appointment_doctor_patient::class);
    }
}
