<?php
namespace App\Services;

use App\Validators\LoginValidator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginService
{
    private $loginValidator;
    private $request;

    public function __construct(LoginValidator $loginValidator, Request $request)
{
    $this->loginValidator = $loginValidator;
    $this->request = $request;
}
public function handleLogin() {
        $this->loginValidator->validateLoginForm();

    $credentials = $this->request->only('email', 'password');
    if (Auth::attempt($credentials)) {
        echo 'Yes';
        redirect()->to('/login/second')->send();
    }
}
}
