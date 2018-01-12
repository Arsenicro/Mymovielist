<?php

namespace Mymovielist\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;
use Mymovielist\User;

class AuthAdmin
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \Closure $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if (Auth::user() != null) {
            $user = new User(Auth::user()->login);
            if ($user->isAdmin()) {
                return $next($request);
            }
        }

        return \redirect()->back()->with('error', "You are not admin!");
    }
}

