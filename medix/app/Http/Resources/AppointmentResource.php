<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AppointmentResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            "appointment_id"=>$this->id,
            "appointment_date"=>$this->appointment_date,
            "appointment_time"=>$this->appointment_time,
            "reason"=>$this->reason,
            "doctor"=>new DoctorResource($this->whenLoaded("doctor")),
            "patient"=>new PatientResource($this->whenLoaded("patient"))
        ];
    }
}
