<?php

namespace App\Http\Middleware;

use App\Models\User;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CheckToken
{
    /**
     * Handle an incoming request.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Closure $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        $token = $request->input('token');
        $password = $request->session()->get('password');
        if (Auth::attempt(['token' => $token, 'password' => $password])) {
            $request->session()->forget('password');
            return redirect()->route('account');
        }
        return $next($request);
    }
}
