<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Redirect;
//

class MobileAuth
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle($request, Closure $next)
    {
        if (! $request->user() || ! $request->user()->hasContactVerified()) {
            return $request->expectsJson()
                ? abort(403, 'mobile_auth')
                : Redirect::route('otp.form');
        }
        return $next($request);
    }
}
