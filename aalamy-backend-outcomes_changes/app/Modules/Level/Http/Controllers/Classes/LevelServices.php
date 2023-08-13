<?php


namespace Modules\Level\Http\Controllers\Classes;



use App\Exceptions\ErrorMsgException;
use Modules\Level\Http\Controllers\Classes\ManageLevel\TeacherLevel;
use App\Exceptions\ErrorUnAuthorizationException;
use App\Http\Controllers\Classes\ApplicationModules;
use App\Modules\Level\Http\Controllers\Classes\ManageLevel\ManageLevelInterface;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Modules\ClassModule\Models\ClassInfo;
use Modules\Level\Http\Controllers\Classes\ManageLesson\LessonManagementFactory;
use Modules\Level\Http\Controllers\Classes\ManageLevel\LevelManagementFactory;
use Modules\Level\Http\Controllers\Classes\ManageSubject\SubjectManagementFactory;
use Modules\Level\Http\Controllers\Classes\ManageUnit\UnitManagementFactory;
use Modules\Level\Models\BaseLevel;
use Modules\Level\Models\BaseLevelSubject;
use Modules\Level\Models\BaseSubject;
use Modules\Level\Models\Lesson;
use Modules\Level\Models\Level;
use Modules\Level\Models\LevelSubject;
use Modules\Level\Models\Subject;
use Modules\Level\Models\Unit;
use Modules\TeacherPermission\Http\Controllers\Classes\PermissionConstraints\LessonPermissionClass;
use Modules\TeacherPermission\Http\Controllers\Classes\PermissionConstraints\UnitPermissionClass;
use Modules\TeacherPermission\Models\Permission;
use Modules\TeacherPermission\Models\PermissionTeacher;
use Modules\User\Http\Controllers\Classes\UserServices;
use Modules\User\Models\School;
use Modules\User\Models\Teacher;
use Modules\User\Models\User;

class LevelServices
{

    public static function checkOwnerLevelAuthorization(User $user,Level $level){
        $levelClass = LevelManagementFactory::create($user);
        $level = $levelClass->myLevelsById($level->id);
        if(is_null($level))
            throw new ErrorUnAuthorizationException();
    }

    public static function checkOwnerSubjectAuthorization(User $user,Subject $subject){
        if($subject->user_id != $user->id)
            throw new ErrorUnAuthorizationException();
    }

    public static function checkAddLevelSubjectAuthorization(Level $level,Subject $subject,User $user){
        Self::checkOwnerLevelAuthorization($user,$level);
        Self::checkOwnerSubjectAuthorization($user,$subject);

    }

    //here we can check on authorization ether level_id or subject_id
    public static function checkOwnerLevelSubjectAuthorization(User $user,LevelSubject $levelSubject){
        $level = Level::findOrFail($levelSubject->level_id);
        Self::checkOwnerLevelAuthorization($user,$level);

    }

    public static function checkUseLevelSubjectAuthorization($levelSubjectId,User $user,$teacherId=null){
        if(!is_null($teacherId)){
            // $myLevelSubjectIds = ClassInfo::where('teacher_id',$teacherId)->pluck('level_subject_id')->toArray();
        	$teacher = Teacher::findOrFail($teacherId);
            $teacherLevel = new TeacherLevel($teacher);
            $myLevelSubjectIds = $teacherLevel->myLevelSubjectsQuery()->pluck('id')->toArray();

            if(!in_array($levelSubjectId,$myLevelSubjectIds))
                throw new  ErrorUnAuthorizationException();
        }else{
            $levelSubject = LevelSubject::findOrFail($levelSubjectId);
            Self::checkOwnerLevelSubjectAuthorization($user,$levelSubject);
        }
    }


    /**
     * @param User $user
     * @param LevelSubject|Builder $levelSubject
     */
    public static function checkAddUnitAuthorization($user,$levelSubject,$teacherId=null){

        if(isset($teacherId)){

            $teacher = Teacher::findOrFail($teacherId);
            $unitPermissionClass = new UnitPermissionClass($teacher);
            $unitPermissionClass->checkIfHavePermission('create')
                ->checkCreate($levelSubject);

            return true;
        }

        $manageClass = SubjectManagementFactory::create($user,$teacherId);
        $mySubjects = $manageClass->mySubjectById($levelSubject->subject_id);
        if(is_null($mySubjects))
            throw new ErrorUnAuthorizationException();

    }

    public static function checkAddLessonAuthorization(User $user,Unit $unit,$teacherId=null){

        if(isset($teacherId)){
            $teacher = Teacher::findOrFail($teacherId);
            $unitPermissionClass = new LessonPermissionClass($teacher);
            $unitPermissionClass->checkIfHavePermission('create')->checkCreate($unit);

            return true;
        }

        $manageClass = UnitManagementFactory::create($user,$teacherId);
        $myUnit = $manageClass->myUnitsById($unit->id);
        if(is_null($myUnit))
            throw new ErrorUnAuthorizationException();

    }


    public static function checkUpdateUnitAuthorization(User $user,Unit $unit,$teacherId=null){
        if(isset($teacherId)){

            $teacher = Teacher::findOrFail($teacherId);
            $unitPermissionClass = new UnitPermissionClass($teacher);
            $unitPermissionClass->checkIfHavePermission('update')
                ->checkUpdate($unit->level_subject_id);


            return true;
        }
        self::checkOwnerUnitAuthorization($user,$unit);
    }

    public static function checkDestroyUnitAuthorization(User $user,Unit $unit,$teacherId=null){
        if(isset($teacherId)){
            $teacher = Teacher::findOrFail($teacherId);
            $unitPermissionClass = new UnitPermissionClass($teacher);
            $unitPermissionClass->checkIfHavePermission('delete')
                ->checkDelete($unit->level_subject_id);

            return true;
        }
        self::checkOwnerUnitAuthorization($user,$unit);

    }

    public static function checkOwnerUnitAuthorization(User $user,Unit $unit,$teacherId=null){
        $manageClass = UnitManagementFactory::create($user,$teacherId);

        $myUnit = $manageClass->myUnitsById($unit->id);
        if(is_null($myUnit))
            throw new ErrorUnAuthorizationException();

    }

    public static function checkUpdateLessonAuthorization(User $user,Lesson $lesson,$teacherId=null){
        if(isset($teacherId)){
            $teacher = Teacher::findOrFail($teacherId);
            $unitPermissionClass = new LessonPermissionClass($teacher);
            $unitPermissionClass->checkIfHavePermission('update')
                ->checkUpdate($lesson->unit_id);

            return true;
        }
        self::checkOwnerLessonAuthorization($user,$lesson);
    }

    public static function checkDestroyLessonAuthorization(User $user,Lesson $lesson,$teacherId=null){
        if(isset($teacherId)){
            $teacher = Teacher::findOrFail($teacherId);
            $unitPermissionClass = new LessonPermissionClass($teacher);
            $unitPermissionClass->checkIfHavePermission('delete')
                ->checkDelete($lesson->unit_id);

            return true;
        }
        self::checkOwnerLessonAuthorization($user,$lesson);
    }

    public static function checkOwnerLessonAuthorization(User $user,Lesson $lesson,$teacherId=null){
        $manageClass = LessonManagementFactory::create($user,$teacherId);

//        $manageClass = Self::createManageLevelClassByType($user->account_type,$user,$teacherId);
        $myLessons = $manageClass->myLessonsById($lesson->id);
        if(count($myLessons)<=0)
            throw new ErrorUnAuthorizationException();

    }


    public static function checkUnitAndLessonBelongsToLevelSubject($levelSubjectId,$unitId=null,$lessonId=null){
        if(!is_null($lessonId)){
            $lesson = Lesson::findOrFail($lessonId);
            if($unitId != $lesson->unit_id)
                throw new ErrorMsgException(transMsg('lesson_is_not_belongs_to_this_unit',ApplicationModules::LEVEL_MODULE_NAME));
        }

        if (!is_null($unitId)){
            $unit = Unit::findOrFail($unitId);
            if($levelSubjectId != $unit->level_subject_id)
                throw new ErrorMsgException(transMsg('unit_is_not_belongs_to_this_levelSubject',ApplicationModules::LEVEL_MODULE_NAME));

        }
    }

//    public static function createDefaultLevels(User $user){
//        $defaultLevels = configFromModule('panel.default_levels',ApplicationModules::LEVEL_MODULE_NAME);
//        $arrayForCreate = [];
//        foreach ($defaultLevels as $defaultLevel){
//            $arrayForCreate[] = [
//              'user_id' => $user->id,
//              'name' => $defaultLevel,
//              'type' => $user->account_type,
//              'created_at' => Carbon::now(),
//            ];
//        }
//        Level::insert($arrayForCreate);
//    }

    public static function createDefaultLevels(User $user){
        $baseLevels = BaseLevel::get();
        foreach ($baseLevels as $baseLevel){
            $arrayForCreate [] = [
                'user_id' => $user->id,
                'base_level_id' => $baseLevel->id,
                'name' => $baseLevel->name,
                'type' => $user->account_type,
                'created_at' => Carbon::now(),
            ];
        }
//        $defaultLevels = configFromModule('panel.default_levels',ApplicationModules::LEVEL_MODULE_NAME);
//        $arrayForCreate = [];
//        foreach ($defaultLevels as $defaultLevel){
//            $arrayForCreate[] = [
//                'user_id' => $user->id,
//                'name' => $defaultLevel,
//                'type' => $user->account_type,
//                'created_at' => Carbon::now(),
//            ];
//        }
        Level::insert($arrayForCreate);
    }

//    public static function createDefaultSubjects(User $user){
//        $defaultSubjects = configFromModule('panel.default_subjects',ApplicationModules::LEVEL_MODULE_NAME);
//        $arrayForCreate = [];
//        foreach ($defaultSubjects as $defaultSubject){
//            $arrayForCreate[] = [
//                'user_id' => $user->id,
//                'name' => $defaultSubject,
//                'type' => $user->account_type,
//                'created_at' => Carbon::now(),
//            ];
//        }
//        Subject::insert($arrayForCreate);
//    }

    public static function createDefaultSubjects(User $user){
        $baseSubjects = BaseSubject::all();
        foreach ($baseSubjects as $baseSubject){
            $arrayForCreate[] = [
                'user_id' => $user->id,
                'base_subject_id' => $baseSubject->id,
                'name' => $baseSubject->name,
                'semester' => $baseSubject->semester,
                'code' => $baseSubject->code,
                'type' => $user->account_type,
                'created_at' => Carbon::now(),
            ];
        }
        Subject::insert($arrayForCreate);

//        $defaultSubjects = configFromModule('panel.default_subjects',ApplicationModules::LEVEL_MODULE_NAME);
//        $arrayForCreate = [];
//        foreach ($defaultSubjects as $defaultSubject){
//            $arrayForCreate[] = [
//                'user_id' => $user->id,
//                'name' => $defaultSubject,
//                'type' => $user->account_type,
//                'created_at' => Carbon::now(),
//            ];
//        }
//        Subject::insert($arrayForCreate);
    }


//    public static function createDefaultLevelSubjects(User $user){
//        $baseLevels = BaseLevel::all();
//        foreach ($baseLevels as $baseLevel){
//            $subjectsIds = BaseLevelSubject::where('base_level_id',$baseLevel->id)
//                ->pluck('base_subject_id')->toArray();
//            foreach ($subjectsIds as $subjectId){
//                LevelSubject::create([
//                   'level_id' =>
//                ]);
//            }
//        }
//    }

    public static function createDefaultEducationalContent(User $user){
        $baseLevels = BaseLevel::isDefault()->get();

        foreach ($baseLevels as $baseLevel){
            $level = Level::create([
                'user_id' => $user->id,
                'base_level_id' => $baseLevel->id,
                'name' => $baseLevel->name,
                'type' => $user->account_type
            ]);

            $subjectsIds = BaseLevelSubject::where('base_level_id',$baseLevel->id)
                ->pluck('base_subject_id')->toArray();
            $baseSubjects = BaseSubject::whereIn('id',$subjectsIds)->get();
            foreach ($baseSubjects as $baseSubject){
                $subject = Subject::where('base_subject_id',$baseSubject->id)
                    ->where('user_id',$user->id)
                    ->first();
                if(is_null($subject)){
                    $subject = Subject::create([
                        'user_id' => $user->id,
                        'base_subject_id' => $baseSubject->id,
                        'name' => $baseSubject->name,
                        'semester' => $baseSubject->semester,
                        'code' => $baseSubject->code,
                        'type' => $user->account_type,
                    ]);
                }

                LevelSubject::create([
                    'level_id' => $level->id,
                    'subject_id' => $subject->id,
                ]);
            }
        }
    }

}
