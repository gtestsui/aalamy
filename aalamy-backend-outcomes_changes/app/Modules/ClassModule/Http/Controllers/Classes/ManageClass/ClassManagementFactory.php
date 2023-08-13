<?php

namespace Modules\ClassModule\Http\Controllers\Classes\ManageClass;

use App\Exceptions\ErrorMsgException;
use App\Http\Controllers\Classes\AbstractManagementFactory;
use Modules\User\Http\Controllers\Classes\UserServices;
use Modules\User\Models\User;

abstract class ClassManagementFactory extends AbstractManagementFactory
{

    /**
     * the key should start with lowercase letter
     * and the value the path of the target class
     */
    protected static $paths = [
        'student' => StudentClassManagement::class,
        'educator' => EducatorClassManagement::class,
        'teacher' => TeacherClassManagement::class,
        'school' => SchoolClassManagement::class,
    ];

    /**
     * return the all array or just item from it
     */
    public static function supportedClasses($key=null){
        return isset($key)
            ?static::$paths[$key]
            :static::$paths;
    }

    public static function create(User $user,$teacherId=null):ManageClassInterface
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
