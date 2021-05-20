<?php
namespace App\Http\Controllers;

use App\Services\AccountPageService;


class AccountPageController
{
    private $accountPageService;

    public function __construct(AccountPageService $accountPageService)
    {
        $this->accountPageService = $accountPageService;
    }

    public function accountPageShow() {
        $this->accountPageService->handleAccountShow();
        $context = $this->accountPageService->getContext();
        return view('accountPage',$context);
    }
    public function accountPageStore() {
        $this->accountPageService->handleAccountShow();
        $this->accountPageService->handleAddMoney();
        $context = $this->accountPageService->getContext();
        return view('accountPage',$context);
    }
}

//todo refresh page after post
