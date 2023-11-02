<?php

namespace App\Http\Requests\ContactDataRecord;

use Illuminate\Foundation\Http\FormRequest;

class EditContactDataRecordRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        //Data validation not implemented
        return [
            'campaign_id'       =>  ['required', 'exists:campaigns,id'],
            'workAvailabilityDays'  =>  ['nullable', 'array']
        ];
    }
}
