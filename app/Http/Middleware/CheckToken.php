<?php

namespace App\Http\Middleware;

use App\Models\User;
use Closure;
use Illuminate\Http\Request;

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
        $request->session()->put(['token' => $request->get('token')]);
        $user = User::where([
            'login_token' => $request->session()->get('token'),
            'email' => $request->session()->get('email')])->first();
        if (!empty($user->email)) {
            return redirect()->route('account');
        }
        return $next($request);
    }
}
