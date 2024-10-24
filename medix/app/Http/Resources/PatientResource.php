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
            "contact"=>$this->contact,
            "insurance_card"=>$this->insurance_card,
            "insurance_card_id"=>$this->insurance_card_id,
            "current_medication"=>$this->current_medication,
            "emergency_contact"=>$this->emergency_contact,
            "Patient_Details"=>new UserResource($this->whenLoaded("user")),
            "Patient_Report"=>PatientReportResource::collection($this->whenLoaded("PatientReport"))
        ];
    }
}
