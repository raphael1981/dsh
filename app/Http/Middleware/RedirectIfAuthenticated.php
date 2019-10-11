<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;
use Mcamara\LaravelLocalization\Facades\LaravelLocalization;

class RedirectIfAuthenticated
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string|null  $guard
     * @return mixed
     */
    public function handle($request, Closure $next, $guard = null)
    {
//        dd(Auth::guard($guard)->check());
        if (Auth::guard($guard)->check()) {
//            return $next($request);
            return redirect(LaravelLocalization::getCurrentLocale().'/administrator');
        }

        return $next($request);
    }
}
