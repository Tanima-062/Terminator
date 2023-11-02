<?php

namespace App\Http\Requests\Intermediary;

use Illuminate\Foundation\Http\FormRequest;
use App\Models\BrokerUser;
use App\Models\User;

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

        return $auth_user->canAccess('intermediares.create');
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
            "phone"               => ['nullable', 'integer'],
        ];
    }

    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            if ($this->email) {
                $company_id = auth()->user()->customer_company_id;
                $exist = User::with('language')->where('email', '=', $this->email)->first();
                if ($exist) {

                    $broker_user = BrokerUser::where('user_id', $exist->id)->first();

                    if($exist->type == User::BROKER_USER){
                        if ($broker_user->customer_company_id == $company_id) {
                            if($broker_user->role == 'Admin'){
                                $validator->errors()->add('email_unique', __('The recorded e-mail address cannot be used at the moment. Please contact your system partner.'), 'en');
                            }else if($broker_user->role == 'Intermediary'){
                                $validator->errors()->add('email_unique', __('The entered email address already exists for an intermediary. The same e-mail address can only be used once for Termin-ator for an active intermediary.'), 'en');
                            }
                        }else{
                            $validator->errors()->add('email_unique', __('The captured email address cannot be used at this time. Please contact Termin-ator IT support.'), 'en');
                        }
                    }

                }
            }
        });
    }

}
