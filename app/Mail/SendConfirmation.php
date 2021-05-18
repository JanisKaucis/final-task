<?php

namespace App\Mail;

use App\Services\LoginService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class SendConfirmation extends Mailable
{
    use Queueable, SerializesModels;

    private $token;

    public function __construct($token)
    {
        $this->token = $token;
    }

    public function build()
    {
        $context = [
            'token' => $this->token,
        ];
        return $this->from('1ee22cca8f-da4906@inbox.mailtrap.io')->
            view('emails.confirmation',$context);
    }
}
