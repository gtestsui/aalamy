<?php

namespace Modules\SubscriptionPlan\Http\Controllers\Classes\PlanConstraints\Factory;

use App\Exceptions\ErrorMsgException;
use App\Http\Controllers\Classes\AbstractManagementFactory;
use Modules\SubscriptionPlan\Http\Controllers\Classes\PlanConstraints\AssignmentCountModuleClass;
use Modules\SubscriptionPlan\Http\Controllers\Classes\PlanConstraints\AssignmentEditorModuleClass;
use Modules\SubscriptionPlan\Http\Controllers\Classes\PlanConstraints\BasePlanConstraintsClass;
use Modules\SubscriptionPlan\Http\Controllers\Classes\PlanConstraints\DownloadAttendanceFileModuleClass;
use Modules\SubscriptionPlan\Http\Controllers\Classes\PlanConstraints\ImportStudentFromExcelModuleClass;
use Modules\SubscriptionPlan\Http\Controllers\Classes\PlanConstraints\ManualNotificationModuleClass;
use Modules\SubscriptionPlan\Http\Controllers\Classes\PlanConstraints\MeetingAttendeeCountModuleClass;
use Modules\SubscriptionPlan\Http\Controllers\Classes\PlanConstraints\MeetingCountModuleClass;
use Modules\SubscriptionPlan\Http\Controllers\Classes\PlanConstraints\MeetingDurationModuleClass;
use Modules\SubscriptionPlan\Http\Controllers\Classes\PlanConstraints\RosterCountModuleClass;
use Modules\SubscriptionPlan\Http\Controllers\Classes\PlanConstraints\StudentAchievementModuleClass;
use Modules\SubscriptionPlan\Http\Controllers\Classes\PlanConstraints\StudentCountModuleClass;
use Modules\SubscriptionPlan\Http\Controllers\Classes\PlanConstraints\TeacherCountModuleClass;
use Modules\User\Http\Controllers\Classes\UserServices;
use Modules\User\Models\School;
use Modules\User\Models\User;

abstract class PlanConstraintsManagementFactory extends AbstractManagementFactory
{

    /**
     * the key should start with lowercase letter
     * and the value the path of the target class
     */
    protected static $paths = [
        'assignment_count' => AssignmentCountModuleClass::class,
        'import_student_from_excel' => ImportStudentFromExcelModuleClass::class,
        'meeting_attendee_count' => MeetingAttendeeCountModuleClass::class,
        'meeting_count' => MeetingCountModuleClass::class,
        'meeting_duration' => MeetingDurationModuleClass::class,
        'roster_count' => RosterCountModuleClass::class,
        'student_count' => StudentCountModuleClass::class,
        'teacher_count' => TeacherCountModuleClass::class,
        'download_attendance_file' => DownloadAttendanceFileModuleClass::class,
        'manual_notification' => ManualNotificationModuleClass::class,
        'student_achievement' => StudentAchievementModuleClass::class,
        'assignment_editor' => AssignmentEditorModuleClass::class,
    ];

    /**
     * return the array or just one item depends on key
     */
    public static function supportedClasses($key=null){
        return isset($key)
            ?static::$paths[$key]
            :static::$paths;
    }

    /**
     * @param User $user
     * @param $modulePlanName
     * @param null $teacherId
     * @return BasePlanConstraintsClass|AssignmentCountModuleClass|ImportStudentFromExcelModuleClass|MeetingAttendeeCountModuleClass|MeetingCountModuleClass|MeetingDurationModuleClass|RosterCountModuleClass|StudentCountModuleClass|TeacherCountModuleClass|DownloadAttendanceFileModuleClass|ManualNotificationModuleClass|StudentAchievementModuleClass|AssignmentEditorModuleClass
     * @throws ErrorMsgException
     */
    private static function create(User $user,$modulePlanName,$teacherId=null):BasePlanConstraintsClass
    {

        if(!key_exists($modulePlanName,self::$paths))
            throw new ErrorMsgException('trying to declare invalid class type ');

        $classPath = self::$paths[$modulePlanName];

        if(isset($teacherId)){
            list(,$teacher) = UserServices::getAccountTypeAndObject($user);
            $school = School::with('User')->findOrFail($teacher->school_id);
            if(class_exists($classPath)){
                return $classPath::createByOther($school->User,$school);
            }

        }else{
            if(class_exists($classPath)){
                return $classPath::createByOwner($user);
            }
        }

        throw new ErrorMsgException('trying to declare invalid class type ');
    }


    public static function createAssignmentCountModule(User $user,$teacher_id=null):AssignmentCountModuleClass{
        return self::create($user,'assignment_count',$teacher_id);
    }

    public static function createImportStudentFromExcelModule(User $user,$teacher_id=null):ImportStudentFromExcelModuleClass{
        return self::create($user,'import_student_from_excel',$teacher_id);
    }

    public static function createMeetingAttendeeCountModule(User $user,$teacher_id=null):MeetingAttendeeCountModuleClass{
        return self::create($user,'meeting_attendee_count',$teacher_id);
    }

    public static function createMeetingCountModule(User $user,$teacher_id=null):MeetingCountModuleClass{
        return self::create($user,'meeting_count',$teacher_id);
    }

    public static function createMeetingDurationModule(User $user,$teacher_id=null):MeetingDurationModuleClass{
        return self::create($user,'meeting_duration',$teacher_id);
    }

    public static function createRosterCountModule(User $user,$teacher_id=null):RosterCountModuleClass{
        return self::create($user,'roster_count',$teacher_id);
    }

    public static function createStudentCountModule(User $user,$teacher_id=null):StudentCountModuleClass{
        return self::create($user,'student_count',$teacher_id);
    }

    public static function createTeacherCountModule(User $user,$teacher_id=null):TeacherCountModuleClass{
        return self::create($user,'teacher_count',$teacher_id);
    }

    public static function createDownloadAttendanceFileModule(User $user,$teacher_id=null):DownloadAttendanceFileModuleClass{
        return self::create($user,'download_attendance_file',$teacher_id);
    }

    public static function createManualNotificationModule(User $user,$teacher_id=null):ManualNotificationModuleClass{
        return self::create($user,'manual_notification',$teacher_id);
    }

    public static function createStudentAchievemntModule(User $user,$teacher_id=null):StudentAchievementModuleClass{
        return self::create($user,'student_achievement',$teacher_id);
    }

    public static function createAssignmentEditorModule(User $user,$teacher_id=null):AssignmentEditorModuleClass{
        return self::create($user,'assignment_editor',$teacher_id);
    }


}
