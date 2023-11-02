<?php

namespace App\Http\Requests\BrokerUser;

use Illuminate\Foundation\Http\FormRequest;
use App\Models\User;
use App\Models\BrokerUser;

use App\Models\CustomerCompanyAdmin;

class StoreRequest extends FormRequest
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

        return $auth_user->canAccess('broker-user:edit');
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            "first_name"          => ['required', 'max:30',],
            "last_name"           => ['required', 'max:30',],
            "email"               => ['required'],
            "phone_iso_code"      => ['nullable', 'max:10'],
            "phone"        => ['nullable', 'integer'],
        ];
    }

    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            if ($this->email) {
                $company_id = auth()->user()->customer_company_id;
                $exist = User::with('language')->where('email', '=', $this->email)->first();
                if ($exist) {

                    $existsSameCompany = BrokerUser::where(['user_id' => $exist->id, 'customer_company_id' => $company_id])->first();

                     if ($existsSameCompany && $exist->type == User::BROKER_USER) {
                          $validator->errors()->add('email_unique_same_company', 'The captured email address already exists for the user type “broker_user”. The same e-mail address can be used for Termin-ator only once for an active user.');
                     } else {
                        $validator->errors()->add('email_unique', __('The captured email address cannot be used at this time. Please contact Termin-ator IT support.'), 'en');
                     }

                }
            }
        });
    }
}
