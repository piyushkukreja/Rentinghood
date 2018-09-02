<?php
/**
 * Created by PhpStorm.
 * User: PIYUSH
 * Date: 25-Jun-18
 * Time: 10:04 PM
 */

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;
class VendorAuthenticate
{
    public function handle($request, Closure $next)
    {
        if (!Auth::user()->isVendor()) {
            return redirect()->route('home');
        }

        return $next($request);
    }
}