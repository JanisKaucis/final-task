<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use PragmaRX\Google2FA\Google2FA;

class AccountPageService
{
    private array $context;
    private Request $request;
    private Google2FA $google2FA;

    public function __construct(Request $request, Google2FA $google2FA)
    {
        $this->request = $request;
        $this->google2FA = $google2FA;
    }

    public function handleAccountShow()
    {
        $user = Auth::user();
        $this->context = [
            'email' => $user->email,
            'bank_account' => $user->bank_account,
            'name' => $user->name,
            'surname' => $user->surname,
            'currency' => $user->currency,
        ];
    }

    public function handleAddMoney()
    {
        $user = Auth::user();
        $userMoney = $user->bank_account;
        $addMoney = $userMoney + $this->request->input('add');
        if ($this->request->input('approve')) {
            User::where('email', $user->email)
                ->update(['bank_account' => $addMoney]);
        }
    }

    public function sendMoney()
    {
        //todo fix double send money on refresh
        $user = Auth::user();
        $google2fa = $this->google2FA;
        $bankUrl = 'https://www.bank.lv/vk/ecb.xml';
        $xml = simplexml_load_file($bankUrl);
        $data = json_encode($xml);
        $data = json_decode($data, true);
        $currencies = $data['Currencies']['Currency'];
        $userMoney = $user->bank_account;
        if ($this->request->input('send')) {
            $recipient = User::firstWhere('email', $this->request->input('email'));
            $secret = $this->request->input('secret');
            if (!empty($secret)) {
                $valid = $google2fa->verifyKey($user->google2fa, $secret);
            }
            if (!empty($recipient->email) && $userMoney >= $this->request->input('amount') && $valid) {
                $removeMoney = $userMoney - $this->request->input('amount');
                foreach ($currencies as $currency) {
                    if ($user->currency == $currency['ID']) {
                        $user_rate_to_eur = 1 / $currency['Rate'];
                    }
                }
                foreach ($currencies as $currency) {
                    if ($recipient->currency == $currency['ID']) {
                        $recipient_rate_from_eur = $currency['Rate'];
                        $addMoney = $recipient->bank_account + $this->request->input('amount') *
                            $user_rate_to_eur * $recipient_rate_from_eur;
                    }
                }

                User::where('email', $user->email)
                    ->update(['bank_account' => $removeMoney]);
                User::where('email', $recipient->email)
                    ->update(['bank_account' => $addMoney]);
            }

        }
    }

    public function getContext(): array
    {
        return $this->context;
    }
}
