<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AppointmentRequest extends FormRequest
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
            "appointment_date"=>"required|date"
            ,"appointment_time"=>"required|date_format:H:i:s"
            ,"reason"=>"string|required|max:255",
            "doctor_id"=>"integer|required",
            "patient_id"=>"integer|required",
        ];
    }
}
