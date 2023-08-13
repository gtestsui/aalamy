<?php

namespace Modules\SubscriptionPlan\Http\Middleware;

use App\Http\Controllers\Classes\ApiResponseClass;
use App\Http\Controllers\Classes\RequestServicesClass;
use Illuminate\Auth\Middleware\Authenticate as Middleware;
use Closure;
use Exception;
use Modules\SubscriptionPlan\Http\Controllers\Classes\SubscriptionPlanServices;

class GetTokenFromRedirectedUrl extends Middleware
{


    public function handle($request, Closure $next)
    {

//        $t = openssl_encrypt('QuestionTypes',"AES-256-ECB",'00000000000000000000000000000000',0);
//        dd(openssl_decrypt($t, "AES-256-ECB", '00000000000000000000000000000000',0));

        if ($request->has('access_token')) {
            $token = RequestServicesClass::getQueryToken($request->get('access_token'));
            $token = SubscriptionPlanServices::decrypt($token);

            $request->headers->set('Authorization', 'Bearer ' . $token);
        }
        return $next($request);
    }
}
