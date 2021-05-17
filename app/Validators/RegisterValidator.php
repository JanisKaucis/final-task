<?php

namespace App\Validators;

use Illuminate\Http\Request;
use Illuminate\Validation\Rules\Password;

class RegisterValidator {

    private $request;

    public function __construct(Request $request)
{
    $this->request = $request;
}

    public function validateRegisterForm()
    {
           $this->request->validate([
               'email' => ['required','email','unique:users,email'],
               'name' => 'required',
               'surname' => 'required',
               'password' => ['required','confirmed', Password::min(6)->letters()->mixedCase()
               ->numbers()],
               'password_confirmation' => ['required']
           ]);

    }
}
