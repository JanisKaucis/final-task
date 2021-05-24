<?php

namespace App\Services;

use App\Models\DepositAccount;
use App\Models\Stocks;
use App\Validators\StockSellValidator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class StocksService
{
    private StockApiService $stockApiService;
    private $context;
    private StockSellValidator $stockSellValidator;
    private Request $request;
    private ConnectToBankLVService $connectToBankLVService;

    public function __construct(StockApiService $stockApiService,
                                StockSellValidator $stockSellValidator,
                                Request $request,
                                ConnectToBankLVService $connectToBankLVService)
    {
        $this->stockApiService = $stockApiService;
        $this->stockSellValidator = $stockSellValidator;
        $this->request = $request;
        $this->connectToBankLVService = $connectToBankLVService;
    }

    public function stocksHandleShow()
    {
        $user = Auth::user();
        $stocks = Stocks::where(['email' => $user->email])->get();
        foreach ($stocks as $stock) {
            $symbol = $stock->symbol;
            $stockPrices = $this->stockApiService->getSymbolPrice($symbol);
        $currentPrice = $stockPrices->getC();
        Stocks::where(['email' => $user->email, 'symbol' => $symbol])
            ->update(['current_price' => $currentPrice]);
        }

        $this->context['stocks'] = $stocks;
        $this->context['symbolError'] = $this->request->session()->get('symbolError');
        $this->request->session()->forget('symbolError');
        $this->context['amountError'] = $this->request->session()->get('amountError');
        $this->request->session()->forget('amountError');
    }

    public function sellStocks()
    {
        $user = Auth::user();
        $this->stockSellValidator->validateStockForm();
        if (empty($this->request->input('sell'))){
            return;
        }
        $symbol = $this->request->input('symbol');
        $amount = $this->request->input('amount');
        $stock = Stocks::firstWhere(['symbol' => $symbol]);
        if (empty($stock->symbol)){
            $this->request->session()->put('symbolError', 'Wrong symbol');
            return;
        }
        if ($amount > $stock->amount || $amount <= 0) {
            $this->request->session()->put('amountError','Invalid Amount');
            return;
        }
        $stockPrices = $this->stockApiService->getSymbolPrice($symbol);
        $currentPrice = $stockPrices->getC();
        $earnings = $amount * $currentPrice;
        $depositAccount = DepositAccount::firstWhere(['parent_account' => $user->email]);
        $depositBalance = $depositAccount->balance;
        if($amount == $stock->amount) {
            Stocks::where(['symbol' => $symbol])->delete();
            DepositAccount::where(['parent_account' => $user->email])
                ->update(['balance' => $depositBalance + $earnings]);
        }else{
            $amountLeft = $stock->amount - $amount;
            $newTotal = $stock->total_price - $earnings;
                    Stocks::where(['symbol' => $symbol])
            ->update(['amount' => $amountLeft,'total_price' => $newTotal]);
                    DepositAccount::where(['parent_account' => $user->email])
                        ->update(['balance' => $depositBalance + $earnings]);
        }

    }
    //todo make conversation from usd to user currency before sending money 
    public function convertBalanceFromUsd()
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
