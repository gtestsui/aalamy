<?php


namespace Modules\StudentAchievement\Http\Controllers\Classes\ManageAchievement;


use App\Exceptions\ErrorUnAuthorizationException;
use App\Http\Controllers\Classes\RequestServicesClass;
use App\Http\Controllers\Classes\ServicesClass;
use Modules\ClassModule\Http\Controllers\Classes\ManageClassStudent\TeacherClassStudent;
use Modules\Level\Http\Controllers\Classes\LevelServices;
use Modules\Notification\Jobs\Achievement\SendNewAchievementNotification;
use Modules\StudentAchievement\Http\DTO\StudentAchievementData;
use Modules\StudentAchievement\Http\Requests\StudentAchievement\StoreStudentAchievementRequest;
use Modules\StudentAchievement\Models\StudentAchievement;
use Modules\User\Http\Controllers\Classes\ManageStudent\StudentEducatorClass;
use Modules\User\Http\Controllers\Classes\ManageStudent\StudentTeacherClass;
use Modules\User\Models\ParentModel;
use Modules\User\Models\ParentStudent;
use Modules\User\Models\Student;
use Modules\User\Models\Teacher;
use Modules\User\Models\User;

class StudentAchievementTeacher extends BaseStudentAchievement
{

    protected Teacher $teacher;
    protected $publishBy='school';
    public function __construct(Teacher $teacher)
    {
        $this->teacher = $teacher;
    }
    public function checkStoreAchievementAuthorization($studentId): Void
    {
        $student = Student::findOrFail($studentId);
        $studentTeacherClass = new StudentTeacherClass($this->teacher);
        $teacherStudent = $studentTeacherClass->myStudentByStudentId($studentId);
        if(is_null($teacherStudent))
            throw new ErrorUnAuthorizationException();

    }

    public function store(StudentAchievementData $studentAchievementData){
        $achievement = StudentAchievement::create($studentAchievementData->all());

        $classStudent = new TeacherClassStudent($this->teacher);
        //get the ids of my classes that contain the student inside
        $classIds = $classStudent->myClassStudentByStudentId($achievement->student_id)
            ->pluck('class_id')
            ->toArray();

        ServicesClass::dispatchJob(
            new SendNewAchievementNotification($classIds,$achievement)
        );
        return $achievement;
    }

    public function getMyStudentAchievementWaitingToPublish(){
        $strudentEducatorClass = new StudentTeacherClass($this->teacher);
        $myStudents = $strudentEducatorClass->myStudents();
        $myStudentIds = $myStudents->pluck('student_id')->toArray();

        $parentIds = ParentStudent::whereIn('student_id',$myStudentIds)->pluck('parent_id')->toArray();
        $parentUserIds = ParentModel::whereIn('id',$parentIds)->pluck('user_id')->toArray();


        return $this->getStudentAchievementWaitingToPublishByUserIds($myStudentIds,$parentUserIds);
    }

}
