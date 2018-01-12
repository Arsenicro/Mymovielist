<?php

namespace Mymovielist\Http\Middleware;

use Closure;
use Mymovielist\User;

class AuthModOrMe
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
        dd($request->route('login'));
        if (Auth::user() != null) {
            $user = new User(Auth::user()->login);
            if ($user->canEdit()) {
                return $next($request);
            }
        }

        return \redirect()->back()->with('error', "You don't have permission");
    }
}
