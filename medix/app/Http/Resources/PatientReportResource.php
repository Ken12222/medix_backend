<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PatientReportResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            "patient_id"=>$this->patient_id,
            "doctor_id"=>$this->doctor_id,
            "symptoms"=>$this->symptoms,
            "doc_report"=>$this->doc_report
        ];
    }
}
