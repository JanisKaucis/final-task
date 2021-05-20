<?php

namespace App\Http\Controllers;

use App\Services\Google2faService;
use Illuminate\Http\Request;

class Google2faController extends Controller
{

    private $google2faService;

    public function __construct(Google2faService $google2faService)
    {
        $this->google2faService = $google2faService;
    }

    public function google2faShow() {
        $this->google2faService->generateGoogle2fa();
        $context = $this->google2faService->getContext();
        return view('google2fa', $context);
    }
}
