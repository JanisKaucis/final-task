<?php

namespace App\Services;

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
        }else{
            $successMessage = 'a';
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
