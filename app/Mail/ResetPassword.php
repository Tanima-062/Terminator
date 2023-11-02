<?php

namespace App\Mail;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\SerializesModels;

class ResetPassword extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    /**
     * The user instance.
     *
     * @var \App\Models\User
     */
    protected $user;

    /**
     * The reset token.
     *
     * @var token
     */
    protected $token;

    /**
     * Create a new message instance.
     *
     * @param  \App\Models\Order  $order
     * @return void
     */
    public function __construct(User $user, $token)
    {
        $this->user = $user;
        $this->token = $token;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $url = url("update-password?token={$this->token}&email={$this->user->email}&lang={$this->user->language->code}");

        return $this->subject(__('Termin-ator Password Reset', [], $this->user->language->code))
            ->view('emails.auth.reset-password', ['url' => $url, 'user' => $this->user])
        ;
    }
}
