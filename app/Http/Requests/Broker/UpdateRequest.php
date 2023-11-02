<?php

namespace App\Http\Requests\Broker;

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

        return  $auth_user->canAccess('broker:edit') && $this->broker->customer_company_id == $auth_user->customer_company_id;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            "name"                                                      => ['required', 'max:50',],
            "street_name"                                               => ['nullable', 'max:30', 'string'],
            "street_number"                                             => ['nullable', 'max:30', 'string'],
            "zip_code"                                                  => ['nullable', 'max:30', 'string'],
            "city"                                                      => ['nullable', 'max:30', 'string'],
            "country_iso_code"                                          => ['nullable', 'max:10'],
            "contact_person_first_name"                                 => ['required', 'max:30', 'regex:/^[\d\p{L}\p{M}\p{Zs}\-]+$/u'],
            "contact_person_last_name"                                  => ['required', 'max:30', 'regex:/^[\d\p{L}\p{M}\p{Zs}\-]+$/u'],
            "contact_person_email"                                      => ['required_without:contact_person_phone', 'nullable', 'email:filter'],
            "contact_person_phone_iso_code"                             => ['required_with:contact_person_phone', 'exclude_without:contact_person_phone', 'max:10'],
            "contact_person_phone"                                      => ['required_without:contact_person_email', 'nullable', 'integer'],
        ];
    }
}
