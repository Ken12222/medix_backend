<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Appointment extends Model
{
    use HasFactory;

    protected $fillable = [
        "appointment_date",
        "appointment_time",
        "reason",
    ];

    public function doctors(){
        return $this->belongsToMany(Doctor::class);
    }

    public function patients(){
        return $this->belongsToMany(Patient::class);
    }
}
