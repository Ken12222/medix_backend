<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Doctor_patient extends Model
{
    use HasFactory;

    public function Doctor(){
        return $this->belongsTo(Doctor::class);
    }

    public function Patient(){
        return $this->belongsTo(Patient::class);
    }
}
