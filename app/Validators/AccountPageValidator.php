<?php
namespace App\Validators;

use Illuminate\Http\Request;

class AccountPageValidator
{
    private Request $request;

    public function __construct(Request $request)
    {
        $this->request = $request;
    }
    public function validateAddMoney() {
        if ($this->request->input('approve')) {
            $this->request->validate([
                'add' => ['required', 'numeric', 'between:0,100000']
            ]);
        }
    }
    public function validateSendMoney(){
        if ($this->request->input('send')) {
            $this->request->validate([
                'email' => ['bail', 'required', 'email', 'exists:users,email'],
                'amount' => ['required', 'numeric', 'between:0,100000'],
                'secret' => ['required']
            ]);
        }
    }
}
