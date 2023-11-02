<?php

namespace App\Http\Requests\Auth;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Rule;
use App\Rules\NotCurrentPassword;
use Illuminate\Validation\Rules\Password;

class ResetPassword extends FormRequest
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
            'password' => ['required', Password::min(8)->letters()->mixedCase()->numbers()->symbols(), new NotCurrentPassword()],
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
            'same' => trans('The Password and Confirm Password must match.'),
        ];
    }
}
