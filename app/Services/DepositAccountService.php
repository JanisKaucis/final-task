<?php

namespace App\Services;

use App\Models\DepositAccount;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DepositAccountService
{
    private $context;
    private Request $request;

    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    public function handleAccountShow()
    {
        $user = Auth::user();
        if ($user->deposit_account == true) {
            $depositAccount = DepositAccount::firstWhere(['parent_account' => $user->email]);
            $this->context['parent_account'] = $depositAccount->parent_account;
            $this->context['deposit'] = $depositAccount->deposit;
            $this->context['balance'] = $depositAccount->balance;
            $this->context['currency'] = $depositAccount->currency;
        }
        $this->context['deposit_account'] = $user->deposit_account;
    }

    public function createDepositAccount()
    {
        $user = Auth::user();
        if ($this->request->input('create') && $user->deposit_account == false) {
            DepositAccount::create([
                'parent_account' => $user->email,
                'deposit' => '0',
                'balance' => '0',
                'currency' => $user->currency
            ]);
            User::where(['email' => $user->email])
            ->update(['deposit_account' => true]);
        }
    }

    public function getContext()
    {
        return $this->context;
    }
}
