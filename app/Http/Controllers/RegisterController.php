<?php

namespace App\Http\Controllers;

use App\Services\RegisterService;

class RegisterController extends Controller
{
    private $registerService;

    public function __construct(RegisterService $registerService)
    {
        $this->registerService = $registerService;
    }

    public function registerCreate() {
    return view('register');
    }
    public function registerStore() {
        $this->registerService->handleStore();
        $context = $this->registerService->getContext();
        return view('register',$context);
    }
}
