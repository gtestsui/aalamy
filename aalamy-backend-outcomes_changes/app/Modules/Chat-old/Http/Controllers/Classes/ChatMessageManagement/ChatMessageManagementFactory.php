<?php

namespace Modules\Chat\Http\Controllers\Classes\ChatMessageManagement;

use App\Exceptions\ErrorMsgException;
use App\Http\Controllers\Classes\AbstractManagementFactory;
use Modules\User\Http\Controllers\Classes\UserServices;
use Modules\User\Models\User;

abstract class ChatMessageManagementFactory  extends AbstractManagementFactory
{

    /**
     * the key should start with lowercase letter
     * and the value the path of the target class
     */
    protected static $paths = [
        'parent' => ParentChatMessage::class,
        'school' => SchoolChatMessage::class,
    ];

    /**
     * return the array or just one item depends on key
     */
    public static function supportedClasses($key=null){
        return isset($key)
            ?static::$paths[$key]
            :static::$paths;
    }

    public static function create(User $user,$teacherId=null):BaseChatMessageClassAbstract
    {
        //we made this to check on teacher type
        list($accountType,$accountObject) = UserServices::getAccountTypeAndObject($user,$teacherId);
        if(!key_exists($accountType,self::$paths))
            throw new ErrorMsgException('trying to declare invalid class type ');

        $classPath = self::$paths[$accountType];
        if(class_exists($classPath)){
            return new $classPath($accountObject);
        }
        throw new ErrorMsgException('trying to declare invalid class type ');
    }


}