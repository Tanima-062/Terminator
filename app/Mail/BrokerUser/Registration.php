<?php

namespace App\Mail\BrokerUser;

use App\Models\BrokerUser;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class Registration extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    /**
     * The user instance.
     *
     * @var \App\Models\User
     */
    protected $broker_user;

    protected $token;

    /**
     * Create a new message instance.
     *
     * @param  \App\Models\Order  $order
     * @return void
     */
    public function __construct(BrokerUser $broker_user)
    {
        $this->broker_user = $broker_user;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
       $email = $this->broker_user->user->email;
       $code = $this->broker_user->user->language->code;
       $url = url("register-invitation?token={$this->broker_user->user->verification_token}&name={$this->broker_user->user->name}&email={$email}&lang={$code}");

        $this->broker_user->role = $this->broker_user->role == 'Admin' ? 'Administrator' : $this->broker_user->role;
        return $this->subject(__('Termin-ator Invitation',[], $this->broker_user->user->language->code))
            ->view('emails.broker-user.registration', ['url' => $url, 'broker_user' => $this->broker_user])
        ;
    }
}
