<?php

namespace App\Http\Middleware;

use Closure;

class HttpsProtocol
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
//        dd($request->getHttpHost());

        if (!$request->secure() && $request->getHttpHost()!=config('services')['domains']['admin']) {
            return redirect()->secure($request->getRequestUri());
        }

        return $next($request);
    }
}
