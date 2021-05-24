<?php

namespace App\Jobs;

use App\Mail\SendConfirmation;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Http\Request;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;

class SendLoginEmail implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private $email;

    public function __construct($email)
    {
        $this->email = $email;
    }

    public function handle()
    {
        $token = uniqid();
        $expireDate = strtotime('+10 minutes');
        User::where('email', $this->email)
            ->update(['token' => $token, 'token_expire_date' => $expireDate]);
        Mail::to($this->email)->send(new SendConfirmation($token));
    }
}
