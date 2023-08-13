<?php

namespace Modules\Level\Http\Controllers\Classes\ManageLevel;

use App\Exceptions\ErrorMsgException;
use App\Http\Controllers\Classes\AbstractManagementFactory;
use App\Modules\Level\Http\Controllers\Classes\ManageLevel\BaseLevelAbstract;
use Modules\User\Http\Controllers\Classes\UserServices;
use Modules\User\Models\User;

abstract class LevelManagementFactory extends AbstractManagementFactory
{

    /**
     * the key should start with lowercase letter
     * and the value the path of the target class
     */
    protected static $paths = [
        'educator' => EducatorLevel::class,
        'school' => SchoolLevel::class,
        'teacher' => TeacherLevel::class,
    ];

    /**
     * return the array or just one item depends on key
     */
    public static function supportedClasses($key=null){
        return isset($key)
            ?static::$paths[$key]
            :static::$paths;
    }


    public static function create(User $user,$teacherId=null):BaseLevelAbstract
    {
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
    }



}
