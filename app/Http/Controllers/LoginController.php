<?php

namespace App\Http\Controllers;

use App\Services\LoginService;

class LoginController extends Controller
{

    private $loginService;

    public function __construct(LoginService $loginService)
    {
        $this->loginService = $loginService;
    }

    public function loginCreate() {
        return view('login');
    }
    public function loginStore() {
        $this->loginService->handleLogin();
        $context = $this->loginService->getContext();
        return view('login',$context);
    }
}
