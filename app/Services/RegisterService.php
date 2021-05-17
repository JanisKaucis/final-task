<?php

namespace App\Services;

use App\Models\User;
use App\Validators\RegisterValidator;
use Illuminate\Http\Request;

class RegisterService {

    private $registerValidator;
    private $request;
    private $context;

    public function __construct(RegisterValidator $registerValidator, Request $request)
    {
        $this->registerValidator = $registerValidator;
        $this->request = $request;
    }

    public function handleStore() {
        $this->registerValidator->validateRegisterForm();
        if ($this->request->isMethod('post')){
            $successMessage = 'You have successfully registered';
            User::create([
               'email' => $this->request->email,
                'name' => $this->request->name,
                'surname' => $this->request->surname,
                'password' => password_hash($this->request->password,PASSWORD_DEFAULT),
                'currency' => $this->request->currency,
            ]);
        }else{
            $successMessage = '';
        }
        $this->context = [
            'success' => $successMessage,
        ];
    }

    public function getContext()
    {
        return $this->context;
    }
}
