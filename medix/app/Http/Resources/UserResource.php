<?php

namespace App\Http\Resources;

use App\Models\Doctor;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Models\User;

class UserResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            "id"=>$this->id,
            "name"=>$this->name,
            "role"=>$this->role,
            "email"=>$this->email,
            "Patient_data"=>PatientResource::collection($this->whenLoaded("Patient")),
            new DoctorResource($this->whenLoaded("Doctor"))
        ];
    }
}
