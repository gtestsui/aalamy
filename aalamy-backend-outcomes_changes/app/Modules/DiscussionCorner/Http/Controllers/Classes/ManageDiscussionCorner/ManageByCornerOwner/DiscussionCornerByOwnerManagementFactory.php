<?php

namespace Modules\DiscussionCorner\Http\Controllers\Classes\ManageDiscussionCorner\ManageByCornerOwner;

use App\Exceptions\ErrorMsgException;
use App\Http\Controllers\Classes\AbstractManagementFactory;

abstract class DiscussionCornerByOwnerManagementFactory/* extends AbstractManagementFactory*/
{

    /**
     * the key should start with lowercase letter
     * and the value the path of the target class
     */
    protected static $paths = [
        'educator' => EducatorDiscussionCorner::class,
        'school' => SchoolDiscussionCorner::class,
    ];

    /**
     * return the array or just one item depends on key
     */
    public static function supportedClasses($key=null){
        return isset($key)
            ?static::$paths[$key]
            :static::$paths;
    }

    public static function create($postOn,$postOwnerObject):ManageDiscussionCorner
    {

        if(!key_exists($postOn,static::supportedClasses()))
            throw new ErrorMsgException('trying to declare invalid class type ');

        $classPath = static::supportedClasses($postOn);
        if(class_exists($classPath)){
            return new $classPath($postOwnerObject);
        }
        throw new ErrorMsgException('trying to declare invalid class type ');


    }


}
