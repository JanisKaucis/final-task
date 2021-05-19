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

    public function loginCreate() {
        return view('secondLogin');
    }

    public function loginStore() {
        $this->secondLoginService->handleToken();
        $context = $this->secondLoginService->getContext();
//        var_dump($context);
        return view('secondLogin',$context);
    }
}
