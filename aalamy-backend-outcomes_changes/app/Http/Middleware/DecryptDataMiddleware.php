<?php

namespace App\Http\Middleware;

use App\Http\Controllers\Classes\ServicesClass;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;

class DecryptDataMiddleware
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
        $array = $this->all();
        dd($array);
        foreach ($array as $param=>$value){
            if(!$this->hasFile($param)){
                $this->merge([
                    $param => $value.'_after_edit'
                ]);
            }
//                dd('s');
        }
        dd($this->all());
        return $next($request);
    }
}
