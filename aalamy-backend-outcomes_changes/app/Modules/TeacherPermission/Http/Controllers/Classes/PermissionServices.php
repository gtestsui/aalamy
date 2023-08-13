<?php

namespace Modules\TeacherPermission\Http\Controllers\Classes;


use App\Exceptions\ErrorMsgException;
use App\Http\Controllers\Classes\ApplicationModules;
use Modules\TeacherPermission\Http\Controllers\Classes\PermissionConstraints\ClassPermissionClass;
use Modules\TeacherPermission\Http\Controllers\Classes\PermissionConstraints\LessonPermissionClass;
use Modules\TeacherPermission\Http\Controllers\Classes\PermissionConstraints\StudentPermissionClass;
use Modules\TeacherPermission\Http\Controllers\Classes\PermissionConstraints\UnitPermissionClass;
use Modules\User\Models\Teacher;

class PermissionServices
{


    private static $targetPermissionsPathes = [
      'unit' => UnitPermissionClass::class,
      'lesson' => LessonPermissionClass::class,
      'class' => ClassPermissionClass::class,
      'student' => StudentPermissionClass::class,
    ];

    /**
     * @param array $keyedPermissions the key is permission container (beginning of the class name in permissionConstraints folder
     */
    public static function isHaveOneOfThisPermissions(Teacher $teacher,array $keyedPermissions){
        foreach ($keyedPermissions as $target =>$permissions) {
            $target = lcfirst($target);
            if(!in_array($target,array_keys(self::$targetPermissionsPathes)))
                throw new ErrorMsgException('invalid target permission name');

            $targetClass = new self::$targetPermissionsPathes[$target]($teacher);
//            dd($permissions);
//            foreach ($permissions as $permission){
                if($targetClass->isHavePermission($permissions))
                    return true;
//            }
        }
        return false;

    }


}
