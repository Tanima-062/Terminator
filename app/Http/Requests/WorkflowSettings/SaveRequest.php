<?php

namespace App\Http\Requests\WorkflowSettings;

use Illuminate\Foundation\Http\FormRequest;

class SaveRequest extends FormRequest
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
        return [
            'call_attempt_limit'                            => 'nullable|integer',
            'contact_limit'                                 => 'nullable|integer',
            'contact_record_creation_cost'                  => 'nullable|numeric',
            'contact_record_creation_revenue'               => 'nullable|numeric',
            'data_verification_cost'                        => 'nullable|numeric',
            'data_verification_revenue'                     => 'nullable|numeric',
            'lead_quality_check_cost'                       => 'nullable|numeric',
            'lead_quality_check_revenue'                    => 'nullable|numeric',
            'edit_lead_quality_topics_cost'                 => 'nullable|numeric',
            'edit_lead_quality_topics_revenue'              => 'nullable|numeric',
            'appointment_contact_cost'                      => 'nullable|numeric',
            'appointment_contact_revenue'                   => 'nullable|numeric',
            'appointment_quality_check_cost'                => 'nullable|numeric',
            'appointment_quality_check_revenue'             => 'nullable|numeric',
            'edit_appointment_quality_topics_cost'          => 'nullable|numeric',
            'edit_appointment_quality_topics_revenue'       => 'nullable|numeric',
            'carry_out_appointment_reminder_cost'           => 'nullable|numeric',
            'carry_out_appointment_reminder_revenue'        => 'nullable|numeric',
        ];
    }
}
