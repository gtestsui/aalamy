<?php


namespace Modules\User\Http\Controllers\Classes\AccountDetails;


use App\Exceptions\ErrorMsgException;
use Modules\User\Http\Controllers\Classes\UserServices;
use Modules\User\Models\User;

class AccountDetailsFactory
{

    /**
     * the key should start with lowercase letter
     * and the value the path of the target class
     */
    private static $paths = [
        'student' => StudentDetailsClass::class,
        'educator' => EducatorDetailsClass::class,
        'parent' => ParentDetailsClass::class,
        'school' => SchoolDetailsClass::class,
    ];

    public static function create(User $user):EducatorAndSchoolDetailsInterface{
        //we made this to check on teacher type
        list($accountType,$accountObject) = UserServices::getAccountTypeAndObject($user);
//        $accountType = strtolower($accountType);
        if(!key_exists($accountType,self::$paths))
            throw new ErrorMsgException('trying to declare invalid class type ');

        $classPath = self::$paths[$accountType];
        if(class_exists($classPath)){
            return new $classPath($accountObject);
        }
        throw new ErrorMsgException('trying to declare invalid class type ');
    }


    /**
     * we have declared this function because sometimes the user who logged in(returned from UserServices::getAccountTypeAndObject)
     * is different from the account I want his details
     */
    public static function createByAccountTypeAndObject($accountType,$accountObject):EducatorAndSchoolDetailsInterface
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
