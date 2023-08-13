<?php

namespace Modules\WorkSchedule\Http\Controllers\Classes\ManageWorkSchedule;

use App\Exceptions\ErrorMsgException;
use App\Http\Controllers\Classes\AbstractManagementFactory;
use Modules\User\Http\Controllers\Classes\UserServices;
use Modules\User\Models\User;

abstract class WorkScheduleManagementFactory extends AbstractManagementFactory
{

    /**
     * the key should start with lowercase letter
     * and the value the path of the target class
     */
    protected static $creatorPaths = [
        'school' => SchoolWorkScheduleClass::class,
    ];

    /**
     * the key should start with lowercase letter
     * and the value the path of the target class
     */
    protected static $readerPaths = [
        'teacher' => TeacherWorkScheduleClass::class,
        'student' => StudentWorkScheduleClass::class,
    ];

    /**
     * return the array or just one item depends on key
     */
    public static function supportedReaderClasses($key=null){
        return isset($key)
            ?static::$readerPaths[$key]
            :static::$readerPaths;
    }

    /**
     * return the array or just one item depends on key
     */
    public static function supportedCreatorClasses($key=null){
        return isset($key)
            ?static::$creatorPaths[$key]
            :static::$creatorPaths;
    }

    public static function createForReader(User $user,$teacherId=null):WorkScheduleReaderInterface
    {
        //we made this to check on teacher type
        list($accountType,$accountObject) = UserServices::getAccountTypeAndObject($user,$teacherId);

        if(!key_exists($accountType,static::supportedReaderClasses()))
            throw new ErrorMsgException('trying to declare invalid class type ');

        $classPath = static::supportedReaderClasses($accountType);
        if(class_exists($classPath)){
            return new $classPath($accountObject);
        }
        throw new ErrorMsgException('trying to declare invalid class type ');
    }

    public static function createForCreator(User $user,$teacherId=null):WorkScheduleCreatorInterface
    {
        //we made this to check on teacher type
        list($accountType,$accountObject) = UserServices::getAccountTypeAndObject($user,$teacherId);
        if(!key_exists($accountType,static::supportedCreatorClasses()))
            throw new ErrorMsgException('trying to declare invalid class type ');

        $classPath = static::supportedCreatorClasses($accountType);
        if(class_exists($classPath)){
            return new $classPath($accountObject);
        }
        throw new ErrorMsgException('trying to declare invalid class type ');
    }


}
