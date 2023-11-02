<?php

namespace App\Mail\CustomerCompanyAdmin;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class Invitaion extends Mailable implements ShouldQueue
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

        return $this->subject(__('Termin-ator Invitation',[], $this->user->language->code))
            ->view('emails.customer-company-admin.invitation', ['url' => $url, 'user' => $this->user])
        ;
    }
}
