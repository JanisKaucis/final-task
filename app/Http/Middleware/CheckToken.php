<?php

namespace App\Http\Middleware;

use App\Jobs\SendLoginEmail;
use App\Models\User;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CheckToken
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
        $user = User::where([
            'login_token' => $request->get('token'),
            'email' => $request->session()->get('email')])->first();
        if (!empty($user->email)) {
            return redirect()->route('account');
        }
        return $next($request);
    }
}
