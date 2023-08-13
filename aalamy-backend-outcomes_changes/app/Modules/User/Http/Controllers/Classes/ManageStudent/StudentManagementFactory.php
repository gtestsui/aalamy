<?php

namespace Modules\User\Http\Controllers\Classes\ManageStudent;

use App\Exceptions\ErrorMsgException;
use App\Http\Controllers\Classes\AbstractManagementFactory;
use App\Modules\User\Http\Controllers\Classes\ManageStudent\BaseManageStudentAbstract;
use App\Modules\User\Http\Controllers\Classes\ManageStudent\ManageStudentParentInterface;
use Modules\User\Http\Controllers\Classes\UserServices;
use Modules\User\Models\Educator;
use Modules\User\Models\ParentModel;
use Modules\User\Models\School;
use Modules\User\Models\Teacher;
use Modules\User\Models\User;

abstract class StudentManagementFactory extends AbstractManagementFactory
{

    /**
     * the key should start with lowercase letter
     * and the value the path of the target class
     */
    protected static $paths = [
        'educator' => StudentEducatorClass::class,
        'panel' => StudentParentClass::class,
        'teacher' => StudentTeacherClass::class,
        'school' => StudentSchoolClass::class,
    ];

    /**
     * return the all array or just item from it
     */
    public static function supportedClasses($key=null){
        return isset($key)
            ?static::$paths[$key]
            :static::$paths;
    }


    /**
     * @param string $accountType
     * @param Educator|Teacher|ParentModel|School $accountObject
     * @return BaseManageStudentAbstract|ManageStudentParentInterface
     */
    public static function create($user):BaseManageStudentAbstract
    {
        //we made this to check on teacher type
        list($accountType,$accountObject) = UserServices::getAccountTypeAndObject($user);
//        $accountType = strtolower($accountType);
        if(!key_exists($accountType,static::supportedClasses()))
            throw new ErrorMsgException('trying to declare invalid class type ');

        $classPath = static::supportedClasses($accountType);
        if(class_exists($classPath)){
            return new $classPath($accountObject);
        }
        throw new ErrorMsgException('trying to declare invalid class type ');
    }

    /**
     * @param string $accountType
     * @param Educator|Teacher|ParentModel|School $accountObject
     * @return BaseManageStudentAbstract|ManageStudentParentInterface
     */
    public static function createByAccountTypeAndObject($accountType,$accountObject):BaseManageStudentAbstract
    {
        //we made this to check on teacher type
//        list($accountType,$accountObject) = UserServices::getAccountTypeAndObject($user,$teacherId);
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
