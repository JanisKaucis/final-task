<?php
namespace App\Services;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use PragmaRX\Google2FA\Google2FA;
use SimpleSoftwareIO\QrCode\Facades\QrCode;


class Google2faService
{
    private $context;

    public function generateGoogle2fa() {

        $google2fa = new Google2FA();
        $companyName = 'The Best Bank';
        $companyEmail = 'janiskaucis@gmail.com';
        $secretKey = $google2fa->generateSecretKey();

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
