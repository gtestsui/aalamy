<?php

namespace Modules\QuestionBank\Http\Controllers\Classes\ManageQuestionLibraryByAccountType\MyAllowedQuestionLibrary;

use App\Exceptions\ErrorMsgException;
use Modules\User\Http\Controllers\Classes\UserServices;
use Modules\User\Models\User;

abstract class MyAllowedQuestionLibraryByAccountTypeManagementFactory /*extends AbstractManagementFactory*/
{

    /**
     * the key should start with lowercase letter
     * and the value the path of the target class
     */
    protected static $paths = [
        'educator' => EducatorMyAllowedQuestionLibraryManagement::class,
        'school' => SchoolMyAllowedQuestionLibraryManagement::class,
        'student' => StudentMyAllowedQuestionLibraryManagement::class,
//        'teacher' => TeacherQuestionLibraryManagement::class,
    ];

    /**
     * return the all array or just item from it
     */
    public static function supportedClasses($key=null){

        return isset($key)
            ?self::$paths[$key]
            :self::$paths;
    }

    public static function create(User $user/*,$teacherId*/):BaseManageMyAllowedQuestionLibraryByAccountTypeAbstract
    {

        //we made this to check on teacher type
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




}
