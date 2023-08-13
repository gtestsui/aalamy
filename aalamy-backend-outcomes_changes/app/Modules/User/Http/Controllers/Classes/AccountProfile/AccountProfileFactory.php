<?php

namespace Modules\User\Http\Controllers\Classes\AccountProfile;

use App\Exceptions\ErrorMsgException;
use Modules\User\Http\Controllers\Classes\UserServices;
use Modules\User\Models\User;

class AccountProfileFactory
{

    /**
     * the key should start with lowercase letter
     * and the value the path of the target class
     */
    private static $paths = [
      'student' => StudentProfile::class,
      'educator' => EducatorProfile::class,
      'parent' => ParentProfile::class,
      'school' => SchoolProfile::class,
    ];

    public static function create(User $user,$teacherId){
        //we made this to check on teacher type
        list($accountType,$accountObject) = UserServices::getAccountTypeAndObject($user,$teacherId);
//        $accountType = strtolower($accountType);
        if(!key_exists($accountType,self::$paths))
            throw new ErrorMsgException('trying to declare invalid class type ');

        $classPath = self::$paths[$accountType];
        if(class_exists($classPath)){
            return new $classPath(/*$accountObject,*/$user);
        }
        throw new ErrorMsgException('trying to declare invalid class type ');
    }

}
