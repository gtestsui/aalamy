<?php

namespace Modules\User\Http\Middleware;

use App\Exceptions\ErrorUnAuthorizationException;
use App\Http\Controllers\Classes\ApplicationModules;
use Closure;
use Illuminate\Http\Request;
use Modules\User\Http\Controllers\Classes\UserServices;

class ApiAdminMiddleware
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
        UserServices::checkRoles($user,['superAdmin']);

        $explodedRoute = explode('/',$request->getRequestUri());
        if($user->account_type != configFromModule('panel.all_account_types.superAdmin',ApplicationModules::USER_MODULE_NAME)){
            $this->checkCanUseSoftDeleteFeature($explodedRoute);
            $this->checkCanUseForceDeleteFeature($explodedRoute);
            $this->checkCanUseCreateOrUpdateHelpCenterFeatures($explodedRoute);
        }


        return $next($request);
    }

    /**
     * check if we use route belongs for forceDelete
     * @param $explodedRoute
     * @throws ErrorUnAuthorizationException
     */
    private function checkCanUseSoftDeleteFeature($explodedRoute){
        if($explodedRoute[count($explodedRoute)-1] == 'delete-or-restore'
            || $explodedRoute[count($explodedRoute)-2] == 'delete-or-restore' ){
            throw new ErrorUnAuthorizationException();

        }
    }

    /**
     * check if we use route belongs for softDelete
     * @param $explodedRoute
     * @throws ErrorUnAuthorizationException
     */
    private function checkCanUseForceDeleteFeature($explodedRoute){
        if($explodedRoute[count($explodedRoute)-1] == 'force-delete'
            || $explodedRoute[count($explodedRoute)-2] == 'force-delete' ){
            throw new ErrorUnAuthorizationException();

        }
    }

    /**
     * check if we use route belongs for softDelete
     * @param $explodedRoute
     * @throws ErrorUnAuthorizationException
     */
    private function checkCanUseCreateOrUpdateHelpCenterFeatures($explodedRoute){
        if($explodedRoute[count($explodedRoute)-1] == 'create'
            || $explodedRoute[count($explodedRoute)-2] == 'update' ){
            throw new ErrorUnAuthorizationException();

        }
    }

}
