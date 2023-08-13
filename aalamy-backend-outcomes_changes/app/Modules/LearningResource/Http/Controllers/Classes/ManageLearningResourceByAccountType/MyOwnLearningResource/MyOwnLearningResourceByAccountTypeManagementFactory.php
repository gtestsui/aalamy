<?php

namespace Modules\LearningResource\Http\Controllers\Classes\ManageLearningResourceByAccountType\MyOwnLearningResource;

use App\Exceptions\ErrorMsgException;
use Modules\User\Http\Controllers\Classes\UserServices;
use Modules\User\Models\User;

abstract class MyOwnLearningResourceByAccountTypeManagementFactory /*extends AbstractManagementFactory*/
{

    /**
     * the key should start with lowercase letter
     * and the value the path of the target class
     */
    protected static $paths = [
        'educator' => EducatorMyOwnLearningResourceManagement::class,
        'school' => SchoolMyOwnLearningResourceManagement::class,
//        'teacher' => TeacherQuestionManagement::class,
    ];

    /**
     * return the all array or just item from it
     */
    public static function supportedClasses($key=null){

        return isset($key)
            ?self::$paths[$key]
            :self::$paths;
    }

    public static function create(User $user/*,$teacherId*/):BaseManageMyOwnLearningResourceByAccountTypeAbstract
    {

        //we made this to check on teacher type ,and we have not sent $teacherId because we don't need him here
        list($accountType,$accountObject) = UserServices::getAccountTypeAndObject($user,true);
//        $accountType = strtolower($accountType);
        if(!key_exists($accountType,self::$paths))
            throw new ErrorMsgException('trying to declare invalid class type ');

        $classPath = self::$paths[$accountType];
        if(class_exists($classPath)){
            return new $classPath($accountObject);
        }
        throw new ErrorMsgException('trying to declare invalid class type ');
    }


    public static function createByAccountTypeAndObject($accountType,$accountObject):BaseManageMyOwnLearningResourceByAccountTypeAbstract
    {

        //we made this to check on teacher type ,and we have not sent $teacherId because we don't need him here
//        list($accountType,$accountObject) = UserServices::getAccountTypeAndObject($user/*,$teacherId*/);
//        $accountType = strtolower($accountType);
        if(!key_exists($accountType,self::$paths))
            throw new ErrorMsgException('trying to declare invalid class type ');

        $classPath = self::$paths[$accountType];
        if(class_exists($classPath)){
            return new $classPath($accountObject);
        }
        throw new ErrorMsgException('trying to declare invalid class type ');
    }




}
