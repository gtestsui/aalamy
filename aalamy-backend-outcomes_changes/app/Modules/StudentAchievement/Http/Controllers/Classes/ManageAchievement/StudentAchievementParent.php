<?php


namespace Modules\StudentAchievement\Http\Controllers\Classes\ManageAchievement;


use App\Exceptions\ErrorUnAuthorizationException;
use App\Http\Controllers\Classes\RequestServicesClass;
use App\Http\Controllers\Classes\ServicesClass;
use Modules\ClassModule\Models\ClassStudent;
use Modules\Notification\Jobs\Achievement\SendNewAchievementWaitingPublishNotification;
use Modules\StudentAchievement\Http\DTO\StudentAchievementData;
use Modules\StudentAchievement\Http\Requests\StudentAchievement\StoreStudentAchievementRequest;
use Modules\StudentAchievement\Models\StudentAchievement;
use Modules\User\Http\Controllers\Classes\ManageStudent\StudentParentClass;
use Modules\User\Models\Educator;
use Modules\User\Models\EducatorStudent;
use Modules\User\Models\ParentModel;
use Modules\User\Models\ParentStudent;
use Modules\User\Models\SchoolStudent;
use Modules\User\Models\Student;
use Modules\User\Models\Teacher;
use Modules\User\Models\User;

class StudentAchievementParent extends BaseStudentAchievement
{

    protected ParentModel $parentModel;
    public function __construct(ParentModel $parentModel)
    {
        $this->parentModel = $parentModel;
    }

    public function checkStoreAchievementAuthorization($studentId): Void
    {
        $student = Student::findOrFail($studentId);
        $studentParentClass = new StudentParentClass($this->parentModel);
        $parentStudent = $studentParentClass->myStudentByStudentId($studentId);
        if(is_null($parentStudent))
            throw new ErrorUnAuthorizationException();
    }

    public function store(StudentAchievementData $studentAchievementData){
        $achievement = StudentAchievement::create($studentAchievementData->all());

        //send notification to my child educators and teachers and schools
        ServicesClass::dispatchJob(
            new SendNewAchievementWaitingPublishNotification($achievement)
        );
        return $achievement;
    }

}
