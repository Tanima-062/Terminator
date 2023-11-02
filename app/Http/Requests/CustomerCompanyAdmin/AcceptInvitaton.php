<?php

namespace App\Http\Requests\CustomerCompanyAdmin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Rule;
use App\Rules\NotCurrentPassword;
use Illuminate\Validation\Rules\Password;
use App\Models\User;

class AcceptInvitaton extends FormRequest
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
            'password' => ['required', Password::min(8)->letters()->mixedCase()->numbers()->symbols()],
            'password_confirmation' => ['required', 'same:password'],
            "token"    =>  ['required'],
            "email"    =>  ['required'],
        ];
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return [
            'password.required' => trans('Password is Required'),
            'password_confirmation.required' => trans('Confirm password  is Required'),
            'password.min' => trans('Password Must Be Atleast 8 Characters'),
            'token.required' => trans('Token is Required'),
            'email.required' => trans('Email is Required'),
            'email.exists' => trans('Sorry, the E-Mail Address you entered is not registered.'),
            'same' => trans('The Password and Confirm Password must match.'),
        ];
    }

    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            if ($this->email && $this->token) {
                $exist = User::where('email', '=', $this->email)->where('verification_token', '!=', $this->user_id)->first();
                if (!$exist) {
                    $validator->errors()->add('token_validation','Token not verified');
                }
            }
        });

    }
}
