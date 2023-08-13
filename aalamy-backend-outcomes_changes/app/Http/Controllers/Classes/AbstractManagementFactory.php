<?php


namespace App\Http\Controllers\Classes;


use App\Exceptions\ErrorMsgException;
use Carbon\Carbon;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\URL;
use Modules\User\Http\Controllers\Classes\UserServices;
use Modules\User\Models\User;

abstract class AbstractManagementFactory
{
    /**
     * the key should start with lowercase letter
     * and the value the path of the target class
     */
    protected static $paths = [

    ];

    abstract public static function supportedClasses($key=null);


    /*public static function create(User $user,$teacherId=null){
        //we made this to check on teacher type
        list($accountType,$accountObject) = UserServices::getAccountTypeAndObject($user,$teacherId);
//        $accountType = strtolower($accountType);
        if(!key_exists($accountType,static::supportedClasses()))
            throw new ErrorMsgException('trying to declare invalid class type ');

        $classPath = static::supportedClasses($accountType);
        if(class_exists($classPath)){
            return new $classPath($accountObject);
        }
        throw new ErrorMsgException('trying to declare invalid class type ');
    }*/
}
