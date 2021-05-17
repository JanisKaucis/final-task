<?php

namespace App\Validators;

use Illuminate\Http\Request;

class LoginValidator
{
    private $request;

    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    public function validateLoginForm()
    {
        $this->request->validate([
            'email' => ['required'],
            'password' => ['required']
        ]);
    }
}
