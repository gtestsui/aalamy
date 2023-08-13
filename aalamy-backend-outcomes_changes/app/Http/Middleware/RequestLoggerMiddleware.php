<?php

namespace App\Http\Middleware;

use App\Http\Controllers\Classes\ServicesClass;
use Carbon\Carbon;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class RequestLoggerMiddleware
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

        if(Auth::guard('api')->check()){
            $user = Auth::guard('api')->user();
            $userInfo = [
                'id'=> $user->id,
                'email'=> $user->email,
                'account_type'=> $user->account_type,
                'fname'=> $user->fname,
                'lname'=> $user->lname,
            ];
            $user->account_type=='educator'?$userInfo['my_teacher_id']=$request->my_teacher_id:null;

        }else{
            $userInfo = null;
        }

        Log::channel('customized_logger')->info('////////////////////////// ');
        Log::channel('customized_logger')->info('start_request');
        Log::channel('customized_logger')->info('user: '.json_encode($userInfo));
        Log::channel('customized_logger')->debug('endpoint: '.$request->path());
        Log::channel('customized_logger')->debug('action: '.$request->route()->getActionName());
        try {
            Log::channel('customized_logger')->debug('request_data: '.json_encode($request->all()));
        }catch (\Exception $e){
            Log::channel('customized_logger')->debug('error_log: '.$e->getMessage());
        }



        return $next($request);
    }
}
