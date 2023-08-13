<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class DefaultTimeZoneMiddleware
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
        date_default_timezone_set(config('panel.timezone','UTC'));

        return $next($request);
    }
}
