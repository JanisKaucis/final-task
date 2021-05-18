<?php
namespace App\Services;

use App\Mail\SendConfirmation;
use App\Models\User;
use App\Validators\LoginValidator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;


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
//        $this->loginValidator->validateLoginForm();

    $credentials = $this->request->only('email', 'password');
    if (Auth::attempt($credentials)) {
        $token = uniqid();
        $expireDate = strtotime('+10 minutes');

        User::where('email', $this->request->input('email'))
            ->update(['login_token' => $token, 'token_expire_date' => $expireDate]);
//        Mail::to($this->request->input('email'))->send(new SendConfirmation($token));
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
