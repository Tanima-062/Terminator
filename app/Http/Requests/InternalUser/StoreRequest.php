<?php

namespace App\Http\Requests\InternalUser;

use Illuminate\Foundation\Http\FormRequest;
use App\Models\User;
use App\Models\InternalUser;

class StoreRequest extends FormRequest
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
            'roles_id'            =>    ['required'],
            'first_name'            =>  ['required', 'max:30'],
            'last_name'             =>  ['required', 'max:30'],
            'email'                 =>  ['required', 'email:filter'],
            'language_id'           =>  ['required'],
            'salutation'            =>  ['required'],
        ];
    }


    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            if ($this->email) {

                $company_id = auth()->user()->customerCompanyAdmin->customer_company_id;
                $type = [
                    'system_admin' => 'The captured email address already exists for the user type “System admin“ The same e-mail address can be used for Termin-ator only once for an active user.',
                    'company_admin' => 'The captured email address already exists for the user type “Company admin“ The same e-mail address can be used for Termin-ator only once for an active user.',
                    'internal_user' => 'The captured email address already exists for the user type “Internal user“ The same e-mail address can be used for Termin-ator only once for an active user.',
                    'broker_user' => 'The captured email address already exists for the user type “Broker user“ The same e-mail address can be used for Termin-ator only once for an active user.'
                ];

                $exist = User::where('email', '=', $this->email)->first();

                if ($exist && ($company_id == $exist->customer_company_id)) {
                    $description = $type[$exist->type];
                    $validator->errors()->add('email_unique_same_company', $description);
                }
                if ($exist && ($exist->customer_company_id !== $company_id)) {
                    $validator->errors()->add('email_unique', 'The Captured email address cannot be used at this time. Please contact Termin-ator IT support.');
                }
            }
        });
    }
}
