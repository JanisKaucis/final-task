<?php

namespace App\Services;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class TransactionsService
{
    private array $transactions = [];
    private $user;
    private ConnectToBankLVService $connectToBankLVService;

    public function __construct(ConnectToBankLVService $connectToBankLVService)
    {
        $this->connectToBankLVService = $connectToBankLVService;
    }

    public function showTransactions()
    {
        $this->connectToBankLVService->connectToBankLV();
        $currencies = $this->connectToBankLVService->getCurrencies();
        $this->user = Auth::user();
        $transactionFile = Storage::disk('local')->get('public/Transactions/'.$this->user->email.'/transactions.json');
        $transactionFile = json_decode($transactionFile, true);
        if (!empty($transactionFile)) {
            foreach ($transactionFile as $record) {
                if ($record['sender_email'] == $this->user->email ||
                    $record['recipient_email'] == $this->user->email) {
                    foreach ($currencies as $currency) {
                        if ($this->user->currency == $currency['ID']) {
                            $record['money_sent'] = round($record['money_eur'] * $currency['Rate'], 2);
                        }
                    }
                    $this->transactions[] = $record;
                }
            }
        }
    }

    public function getTransactions(): array
    {
        return $this->transactions;
    }

    public function getUser()
    {
        return $this->user;
    }
}
