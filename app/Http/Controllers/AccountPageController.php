<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AccountPageController
{
    public function accountPageShow(Request $request) {
        var_dump($request->session()->get('token'));
        var_dump(Auth::check());
        return view('accountPage');
    }
}
