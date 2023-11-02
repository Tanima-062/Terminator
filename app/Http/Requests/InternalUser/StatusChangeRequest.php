<?php

namespace App\Http\Requests\InternalUser;

use Illuminate\Foundation\Http\FormRequest;
use App\Models\User;
use App\Models\InternalUser;

class StatusChangeRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'status'  =>   ['required', 'in:active,inactive'],
            'id'  =>   ['required'],
        ];
    }


    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            if ($this->status == "active") {
                $user_info = User::find($this->id);
                $exist = User::where(['email' => $user_info->email, 'status' => 'active'])->first();
                $type = [
                    'system_admin' => 'The captured email address already exists for the user type “System admin“ The same e-mail address can be used for Termin-ator only once for an active user.',
                    'company_admin' => 'The captured email address already exists for the user type “Company admin“ The same e-mail address can be used for Termin-ator only once for an active user.',
                    'internal_user' => 'The captured email address already exists for the user type “Internal user“ The same e-mail address can be used for Termin-ator only once for an active user.',
                    'broker_user' => 'The captured email address already exists for the user type “Broker user“ The same e-mail address can be used for Termin-ator only once for an active user.'
                ];

                $exist = User::where('email', '=', $this->email)->where('id', '!=', $this->user_id)->first();

                if ($exist && ($exist->customer_company_id == $user_info->customer_company_id)) {
                    $description = $type[$exist->type];
                    $validator->errors()->add('email_unique_same_company', $description);
                }
                if ($exist && ($exist->customer_company_id !== $user_info->customer_company_id)) {
                    $validator->errors()->add('email_unique', 'The Captured email address cannot be used at this time. Please contact Termin-ator IT support.');
                }
            }
        });
    }
}
