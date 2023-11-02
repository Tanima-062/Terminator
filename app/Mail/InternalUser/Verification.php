<?php

namespace App\Mail\InternalUser;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class Verification extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    /**
     * The user instance.
     *
     * @var \App\Models\User
     */
    protected $user;

    /**
     * Create a new message instance.
     *
     * @param  \App\Models\Order  $order
     * @return void
     */
    public function __construct(User $user)
    {
        $this->user = $user;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $url = url("register-invitation?token={$this->user->verification_token}&name={$this->user->name}&email={$this->user->email}&lang={$this->user->language->code}");

        return $this->subject(__('Termin-ator email address verification', [], $this->user->language->code))
            ->view('emails.internal-user.verification', ['url' => $url, 'user' => $this->user]);
    }
}
