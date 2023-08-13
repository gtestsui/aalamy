<?php

namespace Modules\DiscussionCorner\Http\Controllers\Classes\ManageDiscussionCorner\ManageByAccountType;

use App\Exceptions\ErrorMsgException;
use App\Http\Controllers\Classes\AbstractManagementFactory;
use Modules\User\Models\User;

abstract class DiscussionCornerByAccountTypeManagementFactory extends AbstractManagementFactory
{

    /**
     * the key should start with lowercase letter
     * and the value the path of the target class
     */
    protected static $paths = [
        'educator' => EducatorDiscussionCornerClass::class,
        'parent' => ParentDiscussionCornerClass::class,
        'school' => SchoolDiscussionCornerClass::class,
        'student' => StudentDiscussionCornerClass::class,
    ];

    /**
     * return the array or just one item depends on key
     */
    public static function supportedClasses($key=null){
        return isset($key)
            ?static::$paths[$key]
            :static::$paths;
    }

    public static function create(User $user):ManageDiscussionCornerByTypeInterface
    {
        $accountType = $user->account_type;
        if(!key_exists($accountType,static::supportedClasses()))
            throw new ErrorMsgException('trying to declare invalid class type ');

        $classPath = static::supportedClasses($accountType);
        if(class_exists($classPath)){
            return new $classPath($user->{ucfirst($accountType)});
        }
        throw new ErrorMsgException('trying to declare invalid class type ');


    }


}
