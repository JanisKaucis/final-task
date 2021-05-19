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

    public function landingOnPage() {

    }
    public function handleToken() {
        $this->secondLoginValidator->validateLoginForm();
//        $user = User::where(['login_token' => $this->request->get('token')]);

    }

}
