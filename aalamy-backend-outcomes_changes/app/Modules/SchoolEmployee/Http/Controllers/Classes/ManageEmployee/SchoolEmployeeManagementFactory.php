<?php

namespace Modules\SchoolEmployee\Http\Controllers\Classes\ManageEmployee;

use App\Exceptions\ErrorMsgException;
use App\Http\Controllers\Classes\AbstractManagementFactory;
use App\Modules\Level\Http\Controllers\Classes\ManageLevel\BaseLevelAbstract;
use Modules\User\Http\Controllers\Classes\UserServices;
use Modules\User\Models\User;

abstract class SchoolEmployeeManagementFactory extends AbstractManagementFactory
{

    /**
     * the key should start with lowercase letter
     * and the value the path of the target class
     */
    protected static $paths = [
        'employee' => EmployeeClass::class,
        'teacher' => TeacherEmployeeClass::class,
    ];

    /**
     * return the array or just one item depends on key
     */
    public static function supportedClasses($key=null){
        return isset($key)
            ?static::$paths[$key]
            :static::$paths;
    }


    /**
     * @param $type
     * @return BaseManageEmployeeAbstract|TeacherEmployeeClass|EmployeeClass
     * @throws ErrorMsgException
     */
    public static function create($type):BaseManageEmployeeAbstract
    {
        //we made this to check on teacher type
//        list($accountType,$accountObject) = UserServices::getAccountTypeAndObject($user,$teacherId);
//        $accountType = strtolower($accountType);
        if(!key_exists($type,static::supportedClasses()))
            throw new ErrorMsgException('trying to declare invalid class type ');

        $classPath = static::supportedClasses($type);
        if(class_exists($classPath)){
            return new $classPath($type);
        }
        throw new ErrorMsgException('trying to declare invalid class type ');
    }



}
