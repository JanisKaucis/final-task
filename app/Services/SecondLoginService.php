<?php

namespace App\Services;

use App\Mail\SendConfirmation;
use App\Models\User;
use App\Validators\SecondLoginValidator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class SecondLoginService
{
    private $request;
    private $secondLoginValidator;
    private $context;

    public function __construct(Request $request, SecondLoginValidator $secondLoginValidator)
    {
        $this->request = $request;
        $this->secondLoginValidator = $secondLoginValidator;
    }

    public function handleToken()
    {
        $this->secondLoginValidator->validateLoginForm();
        $user = User::where([
            'login_token' => trim($this->request->get('token')),
            'email' => $this->request->session()->get('email')])->first();

        if (empty($user->email)) {
            $loginErr = 'Invalid token';
        } elseif(strtotime("now") > $user->token_expire_date){
            $loginErr = 'Token has expired';
        }else {
            $loginErr = '';
        }
            $this->context = [
            'loginErr' => $loginErr,
        ];
    }

    public function getContext()
    {
        return $this->context;
    }

}
