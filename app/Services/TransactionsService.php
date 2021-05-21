<?php
namespace App\Services;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class TransactionsService
{
    private array $transactions = [];
    private $user;
    public function showTransactions() {
        $this->user = Auth::user();
        $transactionFile = Storage::disk('local')->get('public/Transactions/transactions.json');
        $transactionFile = json_decode($transactionFile,true);
        foreach ($transactionFile as $record) {
            if ($record['sender_email'] == $this->user->email){
                $this->transactions[] = $record;
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
