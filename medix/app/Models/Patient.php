<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Patient extends Model
{
    use HasFactory;

    protected $fillable = [
        "user_id", "doctor_id"
    ];

    public function User(){
        return $this->belongsTo(User::class);
    }

    public function Doctor(){
        return $this->belongsTo(Doctor::class);
    }

    public function PatientReport(){
        return $this->hasMany(PatientReport::class);
    }
}
