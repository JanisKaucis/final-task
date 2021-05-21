<?php

namespace App\Http\Middleware;

use App\Jobs\SendLoginEmail;
use App\Mail\SendConfirmation;
use App\Models\User;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;

class CheckLogin
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        $credentials = $request->only('email', 'password');
        if (Auth::attempt($credentials)) {
            $request->session()->put('email',$request->input('email'));
            $request->session()->save();
            dispatch(new SendLoginEmail($request->session()->get('email')));
           return redirect()->route('login.token');
        }
        return $next($request);
    }
}
