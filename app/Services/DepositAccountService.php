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
    private StockApiService $stockApiService;

    public function __construct(Request $request, StockApiService $stockApiService)
    {
        $this->request = $request;
        $this->stockApiService = $stockApiService;
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
        $this->context['parent_account_balance'] = $user->bank_account;
        $this->context['parent_account_currency'] = $user->currency;
        $this->context['deposit_account'] = $user->deposit_account;
        $this->context['amountError'] = $this->request->session()->get('amountError');
        $this->request->session()->forget('amountError');
        $this->context['companyLogo'] = $this->request->session()->get('companyLogo');
//        $this->request->session()->forget('companyLogo');
        $this->context['companyName'] = $this->request->session()->get('companyName');
//        $this->request->session()->forget('companyName');
        $this->context['companyTicker'] = $this->request->session()->get('companyTicker');
//        $this->request->session()->forget('companyTicker');
        $this->context['stockPrice'] = $this->request->session()->get('stockPrice');
    }

    public function createDepositAccount()
    {
        $user = Auth::user();
        if ($this->request->input('create') && $user->deposit_account == false) {
            DepositAccount::create([
                'parent_account' => $user->email,
                'currency' => $user->currency
            ]);
            User::where(['email' => $user->email])
            ->update(['deposit_account' => true]);
        }
    }
    public function depositMoney() {
        $user = Auth::user();
        $deposit = DepositAccount::select('deposit')->where(['parent_account' => $user->email])->first()->deposit;
        $balance = DepositAccount::select('balance')->where(['parent_account' => $user->email])->first()->balance;

        $parentAccountMoney = $user->bank_account;
        if ($this->request->input('add') > $parentAccountMoney)
        {
            $this->request->session()->put('amountError','Not enough funds');
            return;
        }elseif (!empty($this->request->input('deposit')) &&
            empty($this->request->input('add')) ||
            $this->request->input('add') == '0')
        {
            $this->request->session()->put('amountError','Wrong amount inserted');
            return;
        }
        $addDeposit = $deposit + floatval($this->request->input('add'));
        $addBalance = $balance + floatval($this->request->input('add'));
        DepositAccount::where(['parent_account' => $user->email])
            ->update(['deposit' => $addDeposit]);
        DepositAccount::where(['parent_account' => $user->email])
            ->update(['balance' => $addBalance]);
        User::where(['email' => $user->email])
            ->update(['bank_account' => $user->bank_account - $this->request->input('add')]);
    }
    public function showStockCompany() {
        $symbol = $this->request->input('logo');
        $company = $this->stockApiService->getCompanyProfile($symbol);
        $stockPrice = $this->stockApiService->getSymbolPrice($symbol);
        $this->request->session()->put('companyName',$company->getName());
        $this->request->session()->put('companyLogo',$company->getLogo());
        $this->request->session()->put('companyTicker',$company->getTicker());
        $this->request->session()->put('stockPrice',$stockPrice->getC());
    }

    public function getContext()
    {
        return $this->context;
    }
}
