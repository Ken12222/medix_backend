<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Doctor extends Model
{
    use HasFactory;

    protected $fillable = ["user_id", "phone", "specialty"];

    public function User(){
        return $this->belongsTo(User::class);
    }

    public function Patient(){
        return $this->hasMany(Patient::class);
    }

    public function doctor_patient(){
        return $this->hasMany(Doctor_patient::class);
    }

    public function PatientReport(){
        return $this->hasMany(PatientReport::class);
    }
}
