<?php
namespace App\Services;

use Illuminate\Http\Request;

class SecondLoginService
{
    /**
     * @var Request
     */
    private $request;

    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    public function handleLogin() {
    }

}
