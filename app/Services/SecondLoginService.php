<?php
namespace App\Services;

use App\Mail\SendConfirmation;
use App\Models\User;
use App\Validators\SecondLoginValidator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class SecondLoginService
{
    /**
     * @var Request
     */
    private $request;
    /**
     * @var SecondLoginValidator
     */
    private $secondLoginValidator;

    public function __construct(Request $request, SecondLoginValidator $secondLoginValidator)
    {
        $this->request = $request;
        $this->secondLoginValidator = $secondLoginValidator;
    }

    public function landingOnpage() {
        $token = uniqid();
        $expireDate = strtotime('+10 minutes');
        User::where('email', $this->request->session()->get('email'))
            ->update(['login_token' => $token, 'token_expire_date' => $expireDate]);
        Mail::to($this->request->session()->get('email'))->send(new SendConfirmation($token));
    }
    public function handleToken() {
        $this->secondLoginValidator->validateLoginForm();
    }

}
