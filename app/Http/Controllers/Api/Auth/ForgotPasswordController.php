<?php

namespace App\Http\Controllers\Api\Auth;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;
use App\Mail\ResetPassword;
use App\Models\PasswordReset;
use App\Models\User;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use App\Http\Requests\Auth\ResetPassword as AuthResetPassword;
use Illuminate\Support\Facades\Date;

class ForgotPasswordController extends Controller
{

    /**
     * Display the form to request a password reset link.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function reset(AuthResetPassword $request)
    {
        $user = User::where('email', $request->email)->firstOrFail();

        $token = $user->resetToken()->where('token', $request->token)
            ->where('created_at', '>', Date::now()->subDay())
            ->first();
        if (!$user || !$token) {
            throw ValidationException::withMessages([
                'password' => ['reset_token_invalid'],
            ]);
        }

        $user = User::where('email', $request->email)
            ->update(['password' => Hash::make($request->password)]);

        $token->delete();

        return $this->sendResetLinkResponse('Thank you very much. Your password has been successfully reset. You can now log in with your new password.');
    }
    /**
     * check token
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function tokenValidation(Request $request)
    {
        $user = User::where('email', $request->email)->firstOrFail();

        $token = $user->resetToken()->where('token', $request->token)
            ->where('created_at', '>', Date::now()->subDay())
            ->first();

        if (!$user || !$token) {
            throw ValidationException::withMessages([
                'password' => 'reset_token_invalid',
            ]);
        }

        return true;

    }


    /**
     * Send a reset link to the given user.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Http\JsonResponse
     */
    public function sendResetLinkEmail(Request $request)
    {
        $request->validate([
            'email'  =>  'required|email|exists:users,email'
        ],  [
            'email.exists' => trans('Sorry, the E-Mail Address you entered is not registered.'),
            'email.required' => trans('Required')
        ]);

        $credential = $this->credentials($request);
        $user = User::where($credential)->where('status', User::ACTIVE)->first();

        if (!$user) {
            throw ValidationException::withMessages([
                'email' => [trans('User was not found!')],
            ]);
        }

        PasswordReset::where($credential)->delete();

        $credential['token'] = md5(uniqid() . rand(1000, 9000));
        PasswordReset::create($credential);
        if (Mail::to($user)->send(new ResetPassword($user, $credential['token']))) {
            return $this->sendResetLinkResponse(trans('Password reset link has been sent!'));
        }
    }

    /**
     * Get the needed authentication credentials from the request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    protected function credentials(Request $request)
    {
        if (filter_var(request()->email, FILTER_VALIDATE_EMAIL)) {
            $credential =  ['email' =>  $request->email];
        }
        return $credential;
    }

    /**
     * Get the response for a successful password reset link.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  string  $response
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Http\JsonResponse
     */
    protected function sendResetLinkResponse($response)
    {
        return new JsonResponse(['message' => trans($response)], 200);
    }
}
