<?php

namespace App\Http\Controllers;

use App\Services\LoginService;
use Illuminate\Http\Request;

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
    public function loginStore(Request $request) {
        $request->session()->put('email',$request->input('email'));
        $this->loginService->handleLogin();
        $context = $this->loginService->getContext();
        return view('login',$context);
    }
}
