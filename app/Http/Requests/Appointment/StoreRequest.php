<?php

namespace App\Http\Requests\Appointment;

use Illuminate\Foundation\Http\FormRequest;

class StoreRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return $this->contact_data_record->contact_record_status == "Confirmed";
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'appointment_date' => ['required', 'date', 'after:today'],
            'appointment_time' => ['required', 'regex:/^(?:[01][0-9]|2[0-3]):[0-5][0-9]$/'],
            'notes'            => ['nullable', 'string']
        ];
    }
}
