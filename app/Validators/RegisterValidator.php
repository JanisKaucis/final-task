<?php

namespace App\Validators;

use Illuminate\Http\Request;

class RegisterValidator {

    private $request;

    public function __construct(Request $request)
{
    $this->request = $request;
}

    public function validateRegisterForm()
    {
           $this->request->validate([
               'email' => ['required','email'],
               'name' => 'required',
               'surname' => 'required',
               'password' => ['required','min:6','confirmed'],
               'password_confirmation' => ['required']
           ]);

    }
}
