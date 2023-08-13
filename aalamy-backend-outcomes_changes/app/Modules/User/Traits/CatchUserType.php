<?php

namespace Modules\User\Traits;
use Carbon\Carbon;
use Carbon\CarbonTimeZone;
use Modules\User\Http\Controllers\Classes\UserServices;

trait CatchUserType
{

    protected static $userType/*='student'*/;
    /**
     * @param  mixed  $resource
     * @param String $userType its type the user who do the request
     *
     * if the $userType not null that mean we
     * declared new object from PostResource
     * else that mean we use ::CustomCollection and we make the condition
     * because this values will always passing null if the collection used
     * and we want them values still as initializedValues in CustomCollection
     */
    public function __construct($resource,$userType=null)
    {
        if(!is_null($userType) && in_array($userType,config('User.panel.all_account_types'),true)){

            Self::$userType = $userType;
        }

        Parent::__construct($resource);
    }

    public static function CustomCollection($resource,$userType=null)
    {
        //you can add as many params as you want.
        self::$userType = $userType;
        return Self::collection($resource);
    }
}
