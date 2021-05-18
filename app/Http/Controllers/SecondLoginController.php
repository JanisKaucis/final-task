<?php
namespace App\Http\Controllers;

use App\Services\SecondLoginService;
use Illuminate\Http\Request;

class SecondLoginController
{
    /**
     * @var SecondLoginService
     */
    private $secondLoginService;

    public function __construct(SecondLoginService $secondLoginService)
    {
        $this->secondLoginService = $secondLoginService;
    }

    public function loginCreate(Request $request) {
        $this->secondLoginService->handleLogin();
        var_dump($request->session()->all());
        return view('secondLogin');
    }
}
