<?php

namespace App\Http\Middleware;

use App\Http\Controllers\Classes\ApiResponseClass;
use Illuminate\Auth\Middleware\Authenticate as Middleware;
use Closure;
use Exception;

class Authenticate extends Middleware
{
    /**
     * Get the path the user should be redirected to when they are not authenticated.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return string|null
     */
    protected function redirectTo($request)
    {
        if (! $request->expectsJson()) {
            return route('login');
        }
    }

    public function handle($request, Closure $next, ...$guards)
    {
        try {
            if (empty($guards)) {
                $guards = [null];
            }

            foreach ($guards as $guard) {
                if ($this->auth->guard($guard)->check()) {
                    $this->auth->shouldUse($guard);
                    return $next($request);
                }
            }
            return ApiResponseClass::errorMsgResponse('Invalid Token',403);
//            throw new Exception('Invalid Token');
        }catch(Exception $e){
            return response()->json(['error' => $e->getMessage()], 403);
        }
    }
}
