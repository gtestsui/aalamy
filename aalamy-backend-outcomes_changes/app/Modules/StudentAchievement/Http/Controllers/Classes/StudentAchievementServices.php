<?php


namespace Modules\StudentAchievement\Http\Controllers\Classes;


use App\Exceptions\ErrorMsgException;
use App\Exceptions\ErrorUnAuthorizationException;
use App\Http\Controllers\Classes\FileManagmentServicesClass;
use App\Http\Controllers\Classes\ServicesClass;
use Modules\ClassModule\Http\Controllers\Classes\ManageClassStudent\ManageClassStudentInterface;
use Modules\StudentAchievement\Models\StudentAchievement;
use Modules\User\Http\Controllers\Classes\UserServices;
use Modules\User\Models\User;

class StudentAchievementServices
{

    public static function checkEditStudentAchievement(User $user,StudentAchievement $achievement){
        if($achievement->user_id != $user->id)
            throw new ErrorUnAuthorizationException();
    }

    public static function checkPublishStudentAchievement(ManageClassStudentInterface $classStudentManage,StudentAchievement $achievement){
        $classStduents = $classStudentManage->myClassStudentByStudentId($achievement->student_id);
        if(count($classStduents)==0)
            throw new ErrorUnAuthorizationException();
    }


//    public static function createStudentAchievementClassByType($accountType,User $user,$teacherId):BaseStudentAchievement
//    {
//        $ds = DIRECTORY_SEPARATOR;
//        list($accountType,$accountObject) = UserServices::getAccountTypeAndObject($user,$teacherId);
//
//        $achievementClassNameByType = "StudentAchievement{$accountType}";
//        $achievementClassPathByType = "Modules{$ds}StudentAchievement{$ds}Http{$ds}Controllers{$ds}Classes{$ds}{$achievementClassNameByType}";
//        if(class_exists($achievementClassPathByType)) {
//            $achievementClassByType = new $achievementClassPathByType($accountObject);
//            return $achievementClassByType;
//        }
//        throw new ErrorMsgException('trying to declare invalid class type ');
//
//    }

    public static function deleteAchievement(StudentAchievement $achievement){
        FileManagmentServicesClass::deleteFiles($achievement->file);
//        ServicesClass::DeleteFile($achievement->file);
        $achievement->delete();
    }

}
