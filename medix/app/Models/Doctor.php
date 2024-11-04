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

    public function Patient(){
        return $this->belongsToMany(Patient::class, "doctor_patients");
    }

    public function PatientReport(){
        return $this->hasMany(PatientReport::class);
    }
}
