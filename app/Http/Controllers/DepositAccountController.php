<?php

namespace App\Http\Controllers;

use App\Services\DepositAccountService;
use Illuminate\Http\Request;

class DepositAccountController extends Controller
{
    private DepositAccountService $depositAccountService;
    private Request $request;

    public function __construct(DepositAccountService $depositAccountService,Request $request)
    {
        $this->depositAccountService = $depositAccountService;
        $this->request = $request;
    }

    public function depositAccountShow() {
        $this->depositAccountService->handleAccountShow();
        $this->request->session()->forget('companyName');
        $context = $this->depositAccountService->getContext();
        return view('depositAccount',$context);
    }
    public function depositAccountStore() {
        $this->depositAccountService->createDepositAccount();
        $this->depositAccountService->depositMoney();
        $this->depositAccountService->showStockCompany();
        $this->depositAccountService->buyCompanyStocks();
        $this->depositAccountService->withdrawMoney();
        return redirect()->route('deposit');
//        return view('depositAccount');
    }
}
