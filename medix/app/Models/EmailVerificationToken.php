<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;

class EmailVerificationToken extends Model
{
    use HasFactory, Notifiable;

     protected $fillable = ["email", "token", "expired_at"];
}
