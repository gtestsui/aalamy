<?php

namespace Modules\User\Traits;
use App\Http\Controllers\Classes\ApplicationModules;

trait CatchUserTypeAndObject
{

    protected static $userType;
    protected static $userObject;
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
    public function __construct($resource,$userType=null,$userObject=null)
    {
        if(!is_null($userType) && in_array($userType,configFromModule(
                'panel.all_account_types',
                ApplicationModules::USER_MODULE_NAME
            ),true)){

            Self::$userType = $userType;
            Self::$userObject = $userObject;
        }

        Parent::__construct($resource);
    }


    public static function CustomCollection($resource,$userType=null,$userObject=null)
    {

        if(!is_null($userType) && in_array($userType,configFromModule(
                'panel.all_account_types',
                ApplicationModules::USER_MODULE_NAME
            ),true)){

            //you can add as many params as you want.
            self::$userType = $userType;
            self::$userObject = $userObject;
        }



        return Self::collection($resource);
    }

}
