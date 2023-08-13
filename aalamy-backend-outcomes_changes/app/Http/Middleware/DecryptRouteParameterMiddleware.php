<?php

namespace App\Http\Middleware;

use App\Http\Controllers\Classes\RequestServicesClass;
use App\Http\Controllers\Classes\ServicesClass;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;

class DecryptRouteParameterMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */

    public function __construct()
    {
    }

    public function handle(Request $request, Closure $next)
    {
//        dd($request->route()->parameters);

        foreach ($request->route()->parameters as $paramName=>$param){
            $request->merge([
                $paramName => RequestServicesClass::getParam($param),
            ]);
        }

        return $next($request);
    }
}
