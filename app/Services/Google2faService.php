<?php
namespace App\Services;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use PragmaRX\Google2FA\Google2FA;
use SimpleSoftwareIO\QrCode\Facades\QrCode;


class Google2faService
{
    private $context;
    private Google2FA $google2FA;
    private Request $request;

    public function __construct(Google2FA $google2FA,Request $request)
    {
        $this->google2FA = $google2FA;
        $this->request = $request;
    }
    public function generateGoogle2fa() {

        $companyName = 'The Best Bank';
        $companyEmail = '';
//        $secretKey = $this->google2FA->generateSecretKey();
        $secretKey = 'R4OXNQIGOOGHKMSA';

        $user = Auth::user();
        User::where('email' , $user->email)
            ->update(['google2fa' => $secretKey]);

        $qrCodeUrl = $this->google2FA->getQRCodeUrl(
            $companyName,
            $companyEmail,
            $secretKey
        );
        // Use your own QR Code generator to generate a data URL:
        $qrCode = QrCode::generate($qrCodeUrl);

        $this->context['qrCode'] = $qrCode;
        $this->context['secretKey'] = $secretKey;
        $this->context['error'] = $this->request->session()->get('error');
        $this->request->session()->forget('error');
    }
    public function verifyCode()
    {
        if (empty($this->request->input('send'))) {
            return;
        }
        $user = Auth::user();
        $secret = $this->request->input('verify');
        if (!empty($secret)) {
            $valid = $this->google2FA->verifyKey($user->google2fa, $secret);
        }
        if ($valid) {
            $this->request->session()->put('valid', true);
        }else {
            $this->request->session()->put('error','Your code is not valid');

        }
    }


    public function getContext()
    {
        return $this->context;
    }
}
