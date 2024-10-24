<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PatientResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            "patient_id"=>$this->user_id,
            "doctor_id"=>$this->doctor_id,
            "Patient_Details"=>new UserResource($this->whenLoaded("user")),
            "Patient_Report"=>PatientReportResource::collection($this->whenLoaded("PatientReport"))
        ];
    }
}
