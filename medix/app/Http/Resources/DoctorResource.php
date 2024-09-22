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
            "phone"=>$this->phone,
            "specialty"=>$this->specialty,
            "details"=>new UserResource($this->whenLoaded("user"))
        ];
    }
}
