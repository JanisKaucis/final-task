<?php
namespace App\Services;

use App\Mail\SendConfirmation;
use App\Models\User;
use App\Validators\LoginValidator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;


class LoginService
{
    private $loginValidator;
    private $request;
    private $context;

    public function __construct(LoginValidator $loginValidator, Request $request)
{
    $this->loginValidator = $loginValidator;
    $this->request = $request;
}
public function handleLogin() {
        $this->loginValidator->validateLoginForm();

    $credentials = $this->request->only('email', 'password');
    if (Auth::attempt($credentials)) {
        $loginError = '';
    }else {
        $loginError = 'Invalid email or password';
    }
        $this->context = [
        'loginErr' => $loginError
    ];
}

    public function getContext()
    {
        return $this->context;
    }

}
