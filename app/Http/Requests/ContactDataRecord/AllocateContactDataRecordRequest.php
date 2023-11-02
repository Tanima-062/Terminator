<?php

namespace App\Http\Requests\ContactDataRecord;

use Illuminate\Foundation\Http\FormRequest;

class AllocateContactDataRecordRequest extends FormRequest
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
            'campaign_id'                               =>  ['nullable', 'exists:campaigns,id'],
            'allocate_to'                               =>  ['required',],
            'contact_data_records'                      =>  ['required', 'array', 'min:1',],
            'broker_id'                                 =>  ['nullable', 'required_if:allocate_to,Broker', 'exists:brokers,id',],
            'broker_user_id'                            =>  ['nullable', 'required_if:allocate_to,Broker User', 'exists:broker_users,id',],
            'leader_head_of_user_id'                    =>  ['nullable', 'required_if:allocate_to,Leader Head of', 'exists:internal_users,id',],
            'manager_in_user_id'                        =>  ['nullable', 'required_if:allocate_to,Manager', 'exists:internal_users,id',],
            'quality_controller_user_id'                =>  ['nullable', 'required_if:allocate_to,Quality controller', 'exists:internal_users,id',],
            'call_agent_users_id'                       =>  ['nullable', 'required_if:allocate_to,Call agent', 'exists:internal_users,id',],
            //
            //id	prefix_id	customer_company_id	contact_data_record_id	user_id	broker_user_id	internal_user_id	organization_element_id	broker_id	campaign_id	type	created_at	updated_at
        ];
    }
}
