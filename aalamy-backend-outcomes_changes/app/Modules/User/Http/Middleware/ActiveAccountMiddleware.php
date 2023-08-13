<?php

namespace Modules\User\Http\Middleware;

use App\Exceptions\ErrorMsgException;
use Closure;
use Illuminate\Http\Request;
use Modules\User\Http\Controllers\Classes\UserServices;

class ActiveAccountMiddleware
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
        $user = $request->user();
        if(UserServices::isSuperAdmin($user))
            throw new ErrorMsgException('the admin can\'t use this site');

        UserServices::checkIsActiveAccount($user,$request->my_teacher_id);

        return $next($request);
    }
}
