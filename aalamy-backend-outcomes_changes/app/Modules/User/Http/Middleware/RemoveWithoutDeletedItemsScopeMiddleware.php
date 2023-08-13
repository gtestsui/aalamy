<?php

namespace Modules\User\Http\Middleware;

use App\Http\Controllers\Classes\ServicesClass;
use App\Http\Traits\SoftDelete;
use Closure;
use Illuminate\Http\Request;
use Modules\LearningResource\Models\Topic;
use Modules\User\Http\Controllers\Classes\UserServices;
use Modules\User\Models\User;

class RemoveWithoutDeletedItemsScopeMiddleware
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

        //remove WithOutDeletedItemsScope when the admin request for display deleted items
        //( ignore the deleted column and return all )
        //,and we have used this way to return the related relations with this model even its deleted
        //because sometimes maybe the item belong to another deleted item and if we don't delete
        //the WithOutDeletedItemsScope will return the relation null

        $user = $request->user();

        //first solution (use user or any model has SoftDelete trait)
        if(!is_null($request->route('soft_delete')) && UserServices::isSuperAdmin($user) )
            Topic::removeWithoutDeletedItemsScopeFromAllModel();

//        //second solution
//        ServicesClass::removeWithoutDeletedItemsScopeFromAllModels($request->route('soft_delete'),$user);

        return $next($request);
    }
}
