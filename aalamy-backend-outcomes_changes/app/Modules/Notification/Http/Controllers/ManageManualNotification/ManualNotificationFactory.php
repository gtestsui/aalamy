<?php

namespace Modules\Notification\Http\Controllers\ManageManualNotification;

use App\Exceptions\ErrorMsgException;
use App\Http\Controllers\Classes\AbstractManagementFactory;
use Modules\Notification\Http\Controllers\ManageManualNotification\EducatorManualNotification;
use Modules\Notification\Http\Controllers\ManageManualNotification\SchoolManualNotification;
use Modules\Notification\Http\Controllers\ManageManualNotification\TeacherManualNotification;
use Modules\User\Http\Controllers\Classes\UserServices;
use Modules\User\Models\User;

abstract class ManualNotificationFactory extends AbstractManagementFactory
{

    /**
     * the key should start with lowercase letter
     * and the value the path of the target class
     */
    protected static $paths = [
        'educator' => EducatorManualNotification::class,
        'school' => SchoolManualNotification::class,
        'teacher' => TeacherManualNotification::class,
    ];

    /**
     * return the array or just one item depends on key
     */
    public static function supportedClasses($key=null){
        return isset($key)
            ?static::$paths[$key]
            :static::$paths;
    }

    public static function create(User $user,$teacherId=null):BaseManualNotificationAbstract
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
