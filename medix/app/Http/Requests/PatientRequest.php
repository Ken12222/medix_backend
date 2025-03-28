<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PatientRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            "contact"=>"required|string|max:12", 
            "insurance_card"=>"required|string|max:255", 
            "insurance_card_id"=>"required|string|max:255",
            "current_medication"=>"required|string|max:255", 
            "emergency_contact"=>"required|string|max:255"
        ];
    }
}
