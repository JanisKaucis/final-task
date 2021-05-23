<?php

namespace App\Http\Controllers;

use App\Services\DepositAccountService;
use Illuminate\Http\Request;

class DepositAccountController extends Controller
{
    private DepositAccountService $depositAccountService;

    public function __construct(DepositAccountService $depositAccountService)
    {
        $this->depositAccountService = $depositAccountService;
    }

    public function depositAccountShow() {
        $this->depositAccountService->handleAccountShow();
        $context = $this->depositAccountService->getContext();
        return view('depositAccount',$context);
    }
    public function depositAccountStore() {
        $this->depositAccountService->createDepositAccount();
        $this->depositAccountService->depositMoney();
        return redirect()->route('deposit');
    }
}
