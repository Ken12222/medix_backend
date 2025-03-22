<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;


class DoctorResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            "details"=>["phone"=>$this->phone,
            "specialty"=>$this->specialty,
            "med_licence_no"=>$this->med_licence_number,
            "doctor_id"=>$this->id,],
            "user"=>new UserResource($this->whenLoaded("user"))
        ];
    }
}
