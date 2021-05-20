<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AccountPageService
{
    private array $context;
    private Request $request;

    public function __construct(Request $request)
    {
        $this->request = $request;
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
    public function handleAddMoney() {
        $user = Auth::user();
        $userMoney = $user->bank_account;
        $addMoney = $userMoney + $this->request->input('add');
        if ($this->request->input('approve')) {
            User::where('email',$user->email)
                ->update(['bank_account' => $addMoney]);
        }
    }

    public function getContext(): array
    {
        return $this->context;
    }
}
