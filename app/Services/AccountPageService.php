<?php

namespace App\Services;

use App\Models\User;
use App\Validators\AccountPageValidator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use PragmaRX\Google2FA\Google2FA;

class AccountPageService
{
    private array $context;
    private Request $request;
    private Google2FA $google2FA;
    private AccountPageValidator $accountPageValidator;
    private $currencies;

    public function __construct(Request $request, Google2FA $google2FA, AccountPageValidator $accountPageValidator)
    {
        $this->request = $request;
        $this->google2FA = $google2FA;
        $this->accountPageValidator = $accountPageValidator;
    }

    public function handleAccountShow()
    {
        $user = Auth::user();
        $this->context['email'] = $user->email;
        $this->context['bank_account'] = $user->bank_account;
        $this->context['name'] = $user->name;
        $this->context['surname'] = $user->surname;
        $this->context['currency'] = $user->currency;
        $this->context['success'] = $this->request->session()->get('success');
        $this->request->session()->forget('success');
        $this->context['emailError'] = $this->request->session()->get('emailError');
        $this->request->session()->forget('emailError');
        $this->context['amountError'] = $this->request->session()->get('amountError');
        $this->request->session()->forget('amountError');
    }

    public function handleAddMoney()
    {
        $this->accountPageValidator->validateAddMoney();
        $user = Auth::user();
        $userMoney = $user->bank_account;
        $addMoney = $userMoney + $this->request->input('add');
        if ($this->request->input('approve')) {
            User::where('email', $user->email)
                ->update(['bank_account' => $addMoney]);
            return redirect()->route('account');
        }
    }

    public function sendMoney()
    {
        $this->accountPageValidator->validateSendMoney();
        $user = Auth::user();
        $google2fa = $this->google2FA;
        $userMoney = $user->bank_account;
        $this->connectToBankLV();

        $recipient = User::firstWhere('email', $this->request->input('email'));
        $secret = $this->request->input('secret');
        if (!empty($secret)) {
            $valid = $google2fa->verifyKey($user->google2fa, $secret);
        }
        if (!empty($this->request->input('send'))
            && $userMoney >= $this->request->input('amount')
//                && $valid
        ) {

            $removeMoney = $userMoney - $this->request->input('amount');

            foreach ($this->currencies as $currency) {
                if ($user->currency == $currency['ID']) {
                    $user_rate_to_eur = 1 / $currency['Rate'];
                }
            }
            foreach ($this->currencies as $currency) {
                if ($recipient->currency == $currency['ID']) {
                    $recipient_rate_from_eur = $currency['Rate'];
                    $addMoney = $recipient->bank_account + $this->request->input('amount') *
                        $user_rate_to_eur * $recipient_rate_from_eur;
                }
            }

            if ($this->request->input('email') == $user->email) {
                $this->request->session()->put('emailError', 'You cannot send money to yourself');
            } else {
                User::where('email', $user->email)
                    ->update(['bank_account' => $removeMoney]);
                User::where('email', $recipient->email)
                    ->update(['bank_account' => $addMoney]);

                $this->makeTransaction();
                $this->request->session()->put('success', 'Your transaction was successful');
            }
        }
        if ($userMoney < $this->request->input('amount')) {
            $this->request->session()->put('amountError', 'You do not have so much funds');
        }
    }

    private function makeTransaction()
    {
        $user = Auth::user();
        $recipient = User::firstWhere('email', $this->request->input('email'));

        foreach ($this->currencies as $currency) {
            if ($user->currency == $currency['ID']) {
                $user_rate_to_eur = 1 / $currency['Rate'];
            }
        }
        $transactionData = [
            'sender_email' => $user->email,
            'recipient_email' => $recipient->email,
            'money_sent' => $this->request->input('amount'),
            'money_eur' => round($user_rate_to_eur * $this->request->input('amount'),2),
            'transaction_date' => date('Y-m-d H:i:s')
        ];

        $transactionFile = Storage::disk('local')->get('public/Transactions/transactions.json');
        if (!empty($transactionFile)) {
            $transactionFile = json_decode($transactionFile, true);
            array_push($transactionFile, $transactionData);
            $addTransactionData = json_encode($transactionFile);
        } else {
            $array = [];
            array_push($array, $transactionData);
            $addTransactionData = json_encode($array);
        }
        Storage::disk('local')->put('public/Transactions/transactions.json', $addTransactionData);

    }

    public function connectToBankLV()
    {
        $bankUrl = 'https://www.bank.lv/vk/ecb.xml';
        $xml = simplexml_load_file($bankUrl);
        $data = json_encode($xml);
        $data = json_decode($data, true);
        $this->currencies = $data['Currencies']['Currency'];
    }
    public function getCurrencies()
    {
        return $this->currencies;
    }

    public function getContext(): array
    {
        return $this->context;
    }
}
