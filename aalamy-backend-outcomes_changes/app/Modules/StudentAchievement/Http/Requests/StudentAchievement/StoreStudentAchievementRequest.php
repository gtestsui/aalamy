<?php

namespace Modules\StudentAchievement\Http\Requests\StudentAchievement;

use App\Http\Traits\AuthorizesAfterValidation;
use App\Http\Traits\ResponseValidationFormRequest;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Modules\StudentAchievement\Http\Controllers\Classes\ManageAchievement\BaseStudentAchievement;
use Modules\StudentAchievement\Http\Controllers\Classes\ManageAchievement\StudentAchievementManagementFactory;
use Modules\StudentAchievement\Traits\ValidationAttributesTrans;
use Modules\StudentAchievement\Http\Controllers\Classes\StudentAchievementServices;
use Modules\SubscriptionPlan\Http\Controllers\Classes\PlanConstraints\Factory\PlanConstraintsManagementFactory;
use Modules\User\Http\Controllers\Classes\UserServices;
use Modules\User\Models\Student;
use Modules\User\Models\Teacher;

class StoreStudentAchievementRequest extends FormRequest
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

    private BaseStudentAchievement $achievementClass;
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
        UserServices::checkRoles($user,['parent','school','educator']);
        //here we should check if this is parent the student pr teacher of student or the student in the same school of this school
//        $achievementClassByType = StudentAchievementServices::createStudentAchievementClassByType($user->account_type,$user,$this->my_teacher_id);
        $achievementClassByType = StudentAchievementManagementFactory::create($user,$this->my_teacher_id);
        $achievementClassByType->checkStoreAchievementAuthorization($this->student_id);

        if($user->account_type != 'parent'){
            $studentAchievementModuleClass = PlanConstraintsManagementFactory::createStudentAchievemntModule($user,$this->my_teacher_id);
            $studentAchievementModuleClass->check();
        }

        $this->storeAchievemntClass($achievementClassByType);
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        //if the teacher try to add achievement so should send his my_teacher_id else we will check in his educator account
        return [
//            'student_id' => 'required|exists:students,id',
            'student_id' => 'required|exists:'.(new Student())->getTable().',id',
//            'my_teacher_id' => 'nullable|exists:teachers,id',
            'my_teacher_id' => 'nullable|exists:'.(new Teacher())->getTable().',id',
            'title' => 'required',
            'description' => 'required',
            'file' => 'required|file',
            'file_type' => ['required',Rule::in(config('StudentAchievement.panel.achievements_file_types'))],
        ];
    }

    public function storeAchievemntClass(BaseStudentAchievement $achievementClass){
        $this->achievementClass = $achievementClass;
    }

    public function getAchievemntClass(){
        return $this->achievementClass;
    }

}
