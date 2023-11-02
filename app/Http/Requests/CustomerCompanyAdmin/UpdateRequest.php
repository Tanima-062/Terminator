<?php

namespace App\Http\Requests\CustomerCompanyAdmin;

use App\Models\CustomerCompanyAdmin;
use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;

class UpdateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        // return true;
        $auth_user = auth()->user();

        return $auth_user->canAccess('customer-company-admin:edit');
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'first_name'            =>  ['required', 'max:30'],
            'last_name'             =>  ['required', 'max:30'],
            'email'                 =>  ['required', 'email:filter'],
            'customer_company_id'   =>  ['required', 'exists:customer_companies,id'],
            'language_id'           =>  ['required'],
            'user_id'               =>  ['required']
        ];
    }



    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            if ($this->email) {
                $exist = User::where('email', '=', $this->email)->where('id', '!=', $this->user_id)->first();
                if ($exist) {
                    $type = USER::USER_TYPE[$exist->type];
                    if ($this->customer_company_id == $exist->customer_company_id) {
                        $validator->errors()->add('email_unique_same_company', __('The email address of this :Type already exists for an active :ActiveType. The same email address can be used for Termin-ator only once for an active user.', ['Type' => 'Customer Company Admin', 'ActiveType' => $type], 'en'));
                    } else {
                        $validator->errors()->add('email_unique', __('The email address of this :Type cannot be used at this time. Please contact Termin-ator IT Support.', ['Type' => 'Customer Company Admin']), 'en');
                    }
                }

            }
        });
    }
}
