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

    public function Doctor(){
        return $this->belongsTo(Doctor::class);
    }

    public function Doctor_Patient(){
        return $this->hasMany(Doctor_patient::class);
    }

    public function PatientReport(){
        return $this->hasMany(PatientReport::class);
    }
}
