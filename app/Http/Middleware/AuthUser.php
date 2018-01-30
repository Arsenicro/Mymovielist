<?php

namespace Mymovielist\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class AuthUser
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
            return $next($request);
        }

        if ($request->route()->uri() == 'home') {
            return \redirect()->route('movieList');
        } else {
            return \redirect()->back()->with('error', "You are not logged in!");
        }
    }
}
