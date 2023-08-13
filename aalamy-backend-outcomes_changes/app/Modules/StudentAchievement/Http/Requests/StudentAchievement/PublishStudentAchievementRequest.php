<?php

namespace Modules\StudentAchievement\Http\Requests\StudentAchievement;

use App\Exceptions\ErrorMsgException;
use App\Exceptions\ErrorUnAuthorizationException;
use App\Http\Controllers\Classes\ApplicationModules;
use App\Http\Controllers\Classes\RequestServicesClass;
use App\Http\Traits\AuthorizesAfterValidation;
use App\Http\Traits\ResponseValidationFormRequest;
use Illuminate\Foundation\Http\FormRequest;
use Modules\ClassModule\Http\Controllers\Classes\ManageClassStudent\ClassStudentManagementFactory;
use Modules\StudentAchievement\Traits\ValidationAttributesTrans;
use Modules\StudentAchievement\Http\Controllers\Classes\StudentAchievementServices;
use Modules\StudentAchievement\Models\StudentAchievement;
use Modules\SubscriptionPlan\Http\Controllers\Classes\PlanConstraints\Factory\PlanConstraintsManagementFactory;
use Modules\User\Http\Controllers\Classes\UserServices;

class PublishStudentAchievementRequest extends FormRequest
{

    /**
     * @uses ResponseValidationFormRequest it is responsible to return validation
     * messages error as json
     * @uses AuthorizesAfterValidation it is responsible to call authorizeValidated
     * after check on validation rules
     * @uses ValidationAttributesTrans it is responsible to translate the parameters
     * in rule array
     */
    use ResponseValidationFormRequest,AuthorizesAfterValidation,ValidationAttributesTrans;

    protected $myClassIdsContainTheStudent;
    protected $studentAchievement;
    /**
     * Customized authorization from AuthorizesAfterValidation Trait
     * to check authorize after validation
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorizeAfterValidate()
    {
        $user = $this->user();
        UserServices::checkRoles($user,['educator','school']);
        $achievement = StudentAchievement::findOrFail($this->route('id'));
        list($accountType,$accountObject) = UserServices::getAccountTypeAndObject($user);
        if($achievement->isPublished($accountType))
            throw new ErrorMsgException(transMsg('achievement_has_been_published_before',ApplicationModules::STUDENT_ACHIEVEMENT_MODULE_NAME));

        $classStudentManage = ClassStudentManagementFactory::create($user,$this->my_teacher_id);
        $myClassIdsContainTheStudent = $classStudentManage->myClassStudentByStudentId($achievement->student_id)
            ->pluck('class_id')->toArray();
        if(!count($myClassIdsContainTheStudent))
            throw new ErrorUnAuthorizationException();

//        $studentAchievementModuleClass = PlanConstraintsManagementFactory::createStudentAchievemntModule($user,$this->my_teacher_id);
//        $studentAchievementModuleClass->check();

//        StudentAchievementServices::checkPublishStudentAchievement($classStudentManage,$achievement);
        $this->setClassIdsThatContainsTheStudent($myClassIdsContainTheStudent);
        $this->setStudentAchievement($achievement);
        return true;
    }


    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
        ];
    }

    public function getClassIdsThatContainsTheStudent(){
        return $this->myClassIdsContainTheStudent;
    }

    public function getStudentAchievement(){
        return $this->studentAchievement;
    }

    public function setClassIdsThatContainsTheStudent(array $myClassIdsContainTheStudent){
        $this->myClassIdsContainTheStudent = $myClassIdsContainTheStudent;
    }

    public function setStudentAchievement(StudentAchievement $studentAchievement){
        $this->studentAchievement = $studentAchievement;
    }
}
