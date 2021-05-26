<?php

namespace App\Services;

use App\Models\DepositAccount;
use App\Models\Stocks;
use App\Models\User;
use App\Validators\StockBuyValidator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use function Symfony\Component\Translation\t;

class DepositAccountService
{
    private $context;
    private Request $request;
    private StockApiService $stockApiService;
    private ConnectToBankLVService $connectToBankLVService;
    private $userBalanceInUsd;
    private $usdToEur;
    private StockBuyValidator $stockBuyValidator;

    public function __construct(Request $request,
                                StockApiService $stockApiService,
                                ConnectToBankLVService $connectToBankLVService,
                                StockBuyValidator $stockBuyValidator)
    {
        $this->request = $request;
        $this->stockApiService = $stockApiService;
        $this->connectToBankLVService = $connectToBankLVService;
        $this->stockBuyValidator = $stockBuyValidator;
    }

    public function handleAccountShow()
    {
        $user = Auth::user();
        if ($user->deposit_account == true) {
            $this->convertBalanceToUsd();
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
        $this->context['companyError'] = $this->request->session()->get('CompanyError');
        $this->request->session()->forget('companyError');
        $this->context['companyLogo'] = $this->request->session()->get('companyLogo');
        $this->context['companyName'] = $this->request->session()->get('companyName');
        $this->context['companyTicker'] = $this->request->session()->get('companyTicker');
        $this->context['stockPrice'] = $this->request->session()->get('stockPrice');
        $this->context['balanceInUsd'] = $this->userBalanceInUsd;
        $this->context['infoMessage'] = $this->request->session()->get('infoMessage');
        $this->request->session()->forget('infoMessage');
        $this->context['buyError'] = $this->request->session()->get('buyError');
        $this->request->session()->forget('buyError');
        $this->context['successMessage'] = $this->request->session()->get('successMessage');
        $this->request->session()->forget('successMessage');
        $this->context['withdrawError'] = $this->request->session()->get('withdrawError');
        $this->request->session()->forget('withdrawError');
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

    public function depositMoney()
    {
        if (empty($this->request->input('deposit'))) {
            return;
        }
        $user = Auth::user();
        $deposit = DepositAccount::select('deposit')->where(['parent_account' => $user->email])->first()->deposit;
        $balance = DepositAccount::select('balance')->where(['parent_account' => $user->email])->first()->balance;

        $parentAccountMoney = $user->bank_account;
        if ($this->request->input('add') > $parentAccountMoney) {
            $this->request->session()->put('amountError', 'Not enough funds');
            return;
        } elseif ($this->request->input('add') <= 0) {
            $this->request->session()->put('amountError', 'Invalid amount');
            return;
        } elseif (!empty($this->request->input('deposit')) &&
            empty($this->request->input('add')) ||
            $this->request->input('add') == '0') {
            $this->request->session()->put('amountError', 'Wrong amount inserted');
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

    public function showStockCompany()
    {
        if (empty($this->request->input('find'))) {
            return;
        }
        $this->request->session()->forget('companyName');
        $this->convertBalanceToUsd();
        $this->stockBuyValidator->validateStockLogoForm();
        $symbol = strtoupper($this->request->input('symbol'));
        $this->request->session()->put('symbol', $symbol);
        $company = $this->stockApiService->getCompanyProfile($symbol);
        if (empty($company->getTicker())){
            $this->request->session()->put('CompanyError', 'Could not find this stock company');
            return;
        }
        $stockPrice = $this->stockApiService->getSymbolPrice($symbol);
        $currentPrice = $stockPrice->getC();
        $this->request->session()->put('companyName', $company->getName());
        $this->request->session()->put('companyLogo', $company->getLogo());
        $this->request->session()->put('companyTicker', $company->getTicker());
        $this->request->session()->put('stockPrice', $currentPrice);
        $maxAmountPossible = floor($this->userBalanceInUsd / $currentPrice);
        $budgetLeft = $this->userBalanceInUsd - $maxAmountPossible * $currentPrice;
        $infoMessage = 'You can buy up to ' . $maxAmountPossible . ' stocks with ' . round($budgetLeft, 2) . ' budget left';
        $this->request->session()->put('infoMessage', $infoMessage);
    }

    public function buyCompanyStocks()
    {
        if (empty($this->request->input('buy'))) {
            return;
        }
        $this->stockBuyValidator->validateStockBuyForm();
        $this->connectToBankLVService->connectToBankLV();
        $currencies = $this->connectToBankLVService->getCurrencies();
        $user = Auth::user();
        $depositAccount = DepositAccount::firstWhere(['parent_account' => $user->email]);
        $depositCurrency = $depositAccount->currency;
        $this->convertBalanceToUsd();
        $symbol = $this->request->session()->get('symbol');
        $stockPrice = $this->stockApiService->getSymbolPrice($symbol);
        $company = $this->stockApiService->getCompanyProfile($symbol);
        $currentPrice = $stockPrice->getC();
        $maxAmountPossible = floor($this->userBalanceInUsd / $currentPrice);
        $budgetLeft = $this->userBalanceInUsd - $maxAmountPossible * $currentPrice;
        $infoMessage = 'You can buy up to ' . $maxAmountPossible . ' stocks with ' . round($budgetLeft, 2) . ' budget left';
        $this->request->session()->put('infoMessage', $infoMessage);
        if ($currentPrice > $this->userBalanceInUsd) {
            $this->request->session()->put('buyError', 'You cannot afford this stock');
        }
        $amount = $this->request->input('amount');
        if ($amount > $maxAmountPossible) {
            $this->request->session()->put('buyError', 'You cannot buy this many stocks');
            return;
        } elseif ($amount <= 0) {
            $this->request->session()->put('buyError', 'Invalid amount');
            return;
        }
        $stocksPrice = $amount * $currentPrice;
        foreach ($currencies as $currency) {
            if ($depositCurrency == $currency['ID']) {
                $newBalance = ($this->userBalanceInUsd - $stocksPrice)
                    * $this->usdToEur * $currency['Rate'];
            }
        }
        $this->request->session()->put('successMessage', 'You bought ' . $amount . ' stocks');
        DepositAccount::where(['parent_account' => $user->email])
            ->update(['balance' => $newBalance]);

        $stocks = Stocks::firstWhere([
            'email' => $depositAccount->parent_account,
            'symbol' => $symbol]);
        if (!empty($stocks)) {
            $priceAtBuy = ($stocks->price_at_buy * $stocks->amount + $currentPrice * $amount)
                / ($stocks->amount + $amount);
            $stocksAmount = $stocks->amount;
            $stocksTotalPrice = $stocks->total_price;
        } else {
            $priceAtBuy = $currentPrice;
            $stocksAmount = 0;
            $stocksTotalPrice = 0;
        }
        Stocks::updateOrCreate([
            'email' => $depositAccount->parent_account,
            'symbol' => $symbol],
            [
                'company_name' => $company->getName(),
                'price_at_buy' => $priceAtBuy,
                'amount' => $stocksAmount + $amount,
                'total_price' => $stocksTotalPrice + $stocksPrice,
                'current_price' => $currentPrice,
                'logo' => $company->getLogo(),
            ]);
    }

    public function withdrawMoney()
    {
        if (empty($this->request->input('withdraw'))) {
            return;
        }
        $user = Auth::user();
        $withdrawMoney = $this->request->input('remove');
        $depositAccount = DepositAccount::firstWhere(['parent_account' => $user->email]);
        $removeDeposit = $depositAccount->deposit - $withdrawMoney;
        $removeBalance = $depositAccount->balance - $withdrawMoney;
        $addWithdraw = $user->bank_account + $withdrawMoney;
        if ($removeBalance < 0) {
            $this->request->session()->put('withdrawError', 'You dont have enough funds');
            return;
        } elseif ($withdrawMoney <= 0) {
            $this->request->session()->put('withdrawError', 'Invalid amount');
            return;
        } elseif ($removeDeposit < 0 && $removeBalance >= 0) {
            $sumWithTaxes = abs($removeDeposit);
            $removeDeposit = 0;
            $addWithdraw = $user->bank_account + ($sumWithTaxes * 80 / 100 + $withdrawMoney - $sumWithTaxes);
        }
        DepositAccount::where(['parent_account' => $user->email])
            ->update(['deposit' => $removeDeposit, 'balance' => $removeBalance]);
        User::where(['email' => $user->email])
            ->update(['bank_account' => $addWithdraw]);
    }

    public function convertBalanceToUsd()
    {
        $user = Auth::user();
        $this->connectToBankLVService->connectToBankLV();
        $currencies = $this->connectToBankLVService->getCurrencies();
        foreach ($currencies as $currency) {
            if ($user->currency == $currency['ID']) {
                $userRateToEur = 1 / $currency['Rate'];
            }
        }
        foreach ($currencies as $currency) {
            if ($currency['ID'] == 'USD') {
                $userRateToUsd = $userRateToEur * $currency['Rate'];
                $this->usdToEur = 1 / $currency['Rate'];
            }
        }
        $userAccount = DepositAccount::firstWhere(['parent_account' => $user->email]);
        $userBalance = $userAccount->balance;
        $this->userBalanceInUsd = $userBalance * $userRateToUsd;
    }

    public function getContext()
    {
        return $this->context;
    }
}
