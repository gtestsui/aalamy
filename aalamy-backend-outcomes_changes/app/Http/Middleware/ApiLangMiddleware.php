<?php

namespace App\Http\Middleware;

use App\Http\Controllers\Classes\ServicesClass;
use Carbon\Carbon;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;

class ApiLangMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */

    private $langs;
    public function __construct()
    {
        $this->langs = config('panel.site_languages');
    }

    public function handle(Request $request, Closure $next)
    {
        $requestLang = $request->header('accept-language');

        if(isset($requestLang) && in_array($requestLang,$this->langs))
            App::setLocale($requestLang);
        else
            App::setLocale(config('panel.site_languages.en'));

        return $next($request);
    }
}
