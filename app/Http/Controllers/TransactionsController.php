<?php

namespace App\Http\Controllers;

use App\Services\TransactionsService;
use Illuminate\Http\Request;

class TransactionsController extends Controller
{
    private TransactionsService $transactionsService;

    public function __construct(TransactionsService $transactionsService)
    {
        $this->transactionsService = $transactionsService;
    }

    public function transactionsShow() {
        $this->transactionsService->showTransactions();
        $context = [
            'transactions' => $this->transactionsService->getTransactions(),
            'user' => $this->transactionsService->getUser()];
        return view('transactions',$context);
    }
}
