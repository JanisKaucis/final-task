<?php

namespace App\Http\Controllers;

use App\Services\Google2faService;
use Illuminate\Http\Request;

class Google2faController extends Controller
{

    private $google2faService;
    private Request $request;

    public function __construct(Google2faService $google2faService, Request $request)
    {
        $this->google2faService = $google2faService;
        $this->request = $request;
    }

    public function google2faShow() {
        $this->google2faService->generateGoogle2fa();
        $context = $this->google2faService->getContext();
        return view('google2fa', $context);
    }
    public function google2faStore() {
        $this->google2faService->verifyCode();
        $valid = $this->request->session()->get('valid');
        if ($valid){
            $this->request->session()->forget('valid');
            return redirect()->route('account');
        }
        return redirect()->route('google');
    }
}
