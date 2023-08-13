<?php

namespace Modules\Quiz\Traits;

trait CatchIsQuizOwner
{

    protected static $isOwner=false;
    /**
     * @param  mixed  $resource
     * @param boolean|null $isOwner its type the user who do the request
     *
     * if the $isOwner not null that mean we
     * declared new object from PostResource
     * else that mean we use ::CustomCollection and we make the condition
     * because this values will always passing null if the collection used
     * and we want them values still as initializedValues in CustomCollection
     */
    public function __construct($resource,$isOwner=false)
    {
        //we had put the second condition because without it will
        if(!is_null($isOwner) && $isOwner === true){

            Self::$isOwner = $isOwner;
        }
//        dump('construct : '.self::$isOwner);


        Parent::__construct($resource);
    }

    public static function CustomCollection($resource,$isOwner=null)
    {

        //you can add as many params as you want.
        self::$isOwner = $isOwner;
        return Self::collection($resource);
    }
}
