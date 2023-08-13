<?php

namespace App\Http\Middleware;

use App\Http\Controllers\Classes\ServicesClass;
use Carbon\Carbon;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;

class CatchDataFromHeaderMiddleware
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

        $myTeacherId = $request->header('myTeacherId');
        $timeZone = $request->header('timeZone');
        $acceptedLang = $request->header('accept-language');

//        dd(ServicesClass::toTimezone($request->date,'EET',config('panel.timezone')));

        if(isset($myTeacherId) && !isset($request->my_teacher_id)){
            $request->merge([
                'my_teacher_id' => $myTeacherId
            ]);
        }

        if(isset($timeZone) && !isset($request->time_zone)){
            $request->merge([
                'time_zone' => $timeZone
            ]);
        }

        if(isset($acceptedLang) && !isset($request->lang)){
            $request->merge([
                'lang' => $acceptedLang
            ]);
        }else{
            $request->merge([
                'lang' => config('panel.site_languages.en')
            ]);
        }

        return $next($request);
    }
}
