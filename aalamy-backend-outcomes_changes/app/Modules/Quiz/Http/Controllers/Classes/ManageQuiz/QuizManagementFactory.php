<?php

namespace Modules\Quiz\Http\Controllers\Classes\ManageQuiz;

use App\Exceptions\ErrorMsgException;
use App\Http\Controllers\Classes\AbstractManagementFactory;
use Modules\User\Http\Controllers\Classes\UserServices;
use Modules\User\Models\User;

abstract class QuizManagementFactory extends AbstractManagementFactory
{

    /**
     * the key should start with lowercase letter
     * and the value the path of the target class
     */
    protected static $paths = [
        'educator' => EducatorQuiz::class,
        'school' => SchoolQuiz::class,
        'teacher' => TeacherQuiz::class,
    ];

    protected static $whoCanDisplayPaths = [
        'student' => StudentQuiz::class,

        'educator' => EducatorQuiz::class,
        'school' => SchoolQuiz::class,
        'teacher' => TeacherQuiz::class,
    ];

    /**
     * return the array or just one item depends on key
     */
    public static function supportedClasses($key=null){
        return isset($key)
            ?static::$paths[$key]
            :static::$paths;
    }

    public static function supportedClassesForDisplay($key=null){
        return isset($key)
            ?static::$whoCanDisplayPaths[$key]
            :static::$whoCanDisplayPaths;
    }

    public static function create(User $user):OwnerQuizInterface
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


    public static function createForDisplay(User $user):DisplayQuizInterface
    {
        //we made this to check on teacher type
        list($accountType,$accountObject) = UserServices::getAccountTypeAndObject($user);
//        $accountType = strtolower($accountType);
        if(!key_exists($accountType,static::supportedClassesForDisplay()))
            throw new ErrorMsgException('trying to declare invalid class type ');

        $classPath = static::supportedClassesForDisplay($accountType);
        if(class_exists($classPath)){
            return new $classPath($accountObject);
        }
        throw new ErrorMsgException('trying to declare invalid class type ');


    }


}
