<?php
namespace App\Services;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use PragmaRX\Google2FA\Google2FA;
use SimpleSoftwareIO\QrCode\Facades\QrCode;


class Google2faService
{
    private $context;
    private Google2FA $google2FA;

    public function __construct(Google2FA $google2FA)
    {
        $this->google2FA = $google2FA;
    }

    public function generateGoogle2fa() {

        $google2fa = $this->google2FA;
        $companyName = 'The Best Bank';
        $companyEmail = '';
//        $secretKey = $google2fa->generateSecretKey();
        $secretKey = 'R4OXNQIGOOGHKMSA';

        $user = Auth::user();
        User::where('email' , $user->email)
            ->update(['google2fa' => $secretKey]);

        $qrCodeUrl = $google2fa->getQRCodeUrl(
            $companyName,
            $companyEmail,
            $secretKey
        );
        // Use your own QR Code generator to generate a data URL:
        $qrCode = QrCode::generate($qrCodeUrl);
        $this->context = [
            'qrCode' => $qrCode,
            'secretKey' => $secretKey,
        ];
    }


    public function getContext()
    {
        return $this->context;
    }
}
