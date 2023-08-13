<?php


namespace Modules\ClassModule\Http\Controllers\Classes;


use App\Exceptions\ErrorMsgException;
use App\Exceptions\ErrorUnAuthorizationException;
use App\Http\Controllers\Classes\ApplicationModules;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Modules\ClassModule\Http\Controllers\Classes\ManageClassStudent\ManageClassStudentInterface;
use Modules\ClassModule\Http\DTO\ClassWithClassInfoData;
use Modules\ClassModule\Models\ClassInfo;
use Modules\ClassModule\Models\ClassModel;
use Modules\ClassModule\Models\ClassStudent;
use Modules\Level\Http\Controllers\Classes\LevelServices;
use Modules\Level\Http\Controllers\Classes\ManageLevel\LevelManagementFactory;
use Modules\Level\Models\Level;
use Modules\TeacherPermission\Http\Controllers\Classes\PermissionConstraints\ClassPermissionClass;
use Modules\User\Http\Controllers\Classes\UserServices;
use Modules\User\Models\School;
use Modules\User\Models\Teacher;
use Modules\User\Models\User;

class ClassServices
{

    public static function checkOwnerClassAuthorization(User $user,$levelId){
        $level = Level::findOrFail($levelId);

        LevelServices::checkOwnerLevelAuthorization($user,$level);
    }

    public static function checkIfTeacherHasPermissionOnThisClass(ClassModel $class,$teacherId){
        $classInfo = ClassInfo::where('teacher_id',$teacherId)
            ->where('class_id',$class->id)->first();
        if(is_null($classInfo))
            throw new ErrorUnAuthorizationException();
    }


    public static function checkStoreClassAuthorization(User $user,$levelId,$teacherId=null){
        if (isset($teacherId)){
            $teacher = Teacher::findOrFail($teacherId);
            $classPermissionClass = new ClassPermissionClass($teacher);
            $classPermissionClass->checkIfHavePermission('create')
                ->checkCreate($levelId);
            return true;

        }

        Self::checkOwnerClassAuthorization($user,$levelId);
    }


    public static function checkUpdateClassAuthorization(User $user,$levelId,$teacherId=null){
        if (isset($teacherId)){
            $teacher = Teacher::findOrFail($teacherId);
            $classPermissionClass = new ClassPermissionClass($teacher);
            $classPermissionClass->checkIfHavePermission('update')
                ->checkUpdate($levelId);
            return true;

        }

        Self::checkOwnerClassAuthorization($user,$levelId);
    }

    public static function checkShowClassAuthorization(User $user,ClassModel $class){
        $levelId = $class->level_id;
        if($user->account_type == config('User.panel.all_account_types.student')){
            $user->load('Student');
            $classStudent = ClassStudent::active()
                ->where('student_id',$user->Student->id)
                ->where('class_id',$class->id)
                ->first();
            if(is_null($classStudent))
                throw new ErrorUnAuthorizationException();

            return true;
        }
        Self::checkOwnerClassAuthorization($user,$levelId);
    }


    public static function checkDeleteClassAuthorization(User $user,$levelId,$teacherId=null){
        if (isset($teacherId)){
            $teacher = Teacher::findOrFail($teacherId);
            $classPermissionClass = new ClassPermissionClass($teacher);
            $classPermissionClass->checkIfHavePermission('delete')
                ->checkDelete($levelId);
            return true;

        }

        Self::checkOwnerClassAuthorization($user,$levelId);
    }

    public static function checkAddStudentToClassAuthorization(User $user,ClassModel $class,$teacherId=null){
        if(is_null($teacherId))
            Self::checkOwnerClassAuthorization($user,$class->level_id);
        else{
            Self::checkIfTeacherHasPermissionOnThisClass($class,$teacherId);
        }
    }

    public static function checkGetStudentFromClassAuthorization(User $user,ClassModel $class,$teacherId=null){
        if(is_null($teacherId))
            Self::checkOwnerClassAuthorization($user,$class->level_id);
        else{
            Self::checkIfTeacherHasPermissionOnThisClass($class,$teacherId);

        }

    }

    public static function checkDeleteStudentFromClassAuthorization(User $user,ClassModel $class,$teacherId=null){
        if(is_null($teacherId))
            Self::checkOwnerClassAuthorization($user,$class->level_id);
        else{
            Self::checkIfTeacherHasPermissionOnThisClass($class,$teacherId);

        }
    }

    public static function checkOwnerTeacherAuthorization(School $school,Teacher $teacher){
        if($teacher->school_id != $school->id)
            throw new ErrorUnAuthorizationException();
    }


    public static function checkStudentAlreadyBelongsToClass($classId,$studentId){
        $classStudent = ClassStudent::where('student_id',$studentId)
            ->where('class_id',$classId)
            ->active()
            ->first();
        if(!is_null($classStudent))
            throw new ErrorMsgException(transMsg('cant_move_student_to_class_he_belongs_to_it_already',ApplicationModules::CLASS_MODULE_NAME));

    }

//    /**
//     * @return Collection of ClassStudent
//     */
//    public static function checkIfStudentsBelongsToClass(array $studentIds , $classId){
//
//        //get the students belong to this class
//        $classStudents = ClassStudent::whereIn('student_id',$studentIds)
//            ->where('class_id',$classId)->get();
//
//        //check if all student id from request belongs to this class
//        $classStudentCount = $classStudents->count();
//        if($classStudentCount < count($studentIds))
//            throw new ErrorMsgException('you are trying to add student not in the roster class');
//
//        return $classStudents;
//    }


    public static function createMoreThanClassInfo($classId,ClassWithClassInfoData $classData){

        $arrayForCreate = [];
        foreach ($classData->level_subject_ids as $levelSubjectId){
            $arrayForCreate [] = [
                'class_id' => $classId,
                'school_id' => $classData->school_id,
                'teacher_id' => $classData->teacher_id,
                'educator_id' => $classData->educator_id,
                'level_subject_id' => $levelSubjectId,
                'created_at' => Carbon::now(),
            ];
        }
        ClassInfo::insert($arrayForCreate);
    }


    public static function addStudentToClass($accountType,$accountObject,$studentId,$classId){
        return ClassStudent::create([
            'student_id'=> $studentId,
            'class_id'=> $classId,
            $accountType.'_id'=>  $accountObject->id,
            'study_year'=> Carbon::now(),
        ]);
    }


}
