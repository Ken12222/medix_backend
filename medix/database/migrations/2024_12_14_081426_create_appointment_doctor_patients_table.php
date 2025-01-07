<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('appointment_doctor_patients', function (Blueprint $table) {
            $table->id();
                        $table->foreignId("doctor_id")->constrained("doctors")->onDelete("cascade");
            $table->foreignId("patient_id")->constrained("patients")->onDelete("cascade");
            $table->foreignId("appointment_id")->constrained("appointment")->onDelete("cascade");
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('appointment_doctor_patients');
    }
};
