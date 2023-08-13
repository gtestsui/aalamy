<?php

namespace Modules\Roster\Http\Requests\Roster;

use App\Http\Traits\AuthorizesAfterValidation;
use App\Http\Traits\ResponseValidationFormRequest;
use Illuminate\Foundation\Http\FormRequest;
use Modules\ClassModule\Models\ClassInfo;
use Modules\Roster\Http\Controllers\Classes\RosterServices;
use Modules\Roster\Traits\ValidationAttributesTrans;
use Modules\SubscriptionPlan\Http\Controllers\Classes\PlanConstraints\Factory\PlanConstraintsManagementFactory;
use Modules\SubscriptionPlan\Http\Controllers\Classes\PlanConstraints\RosterCountModuleClass;
use Modules\User\Http\Controllers\Classes\UserServices;
use Modules\User\Models\School;
use Modules\User\Models\Teacher;

class StoreRosterRequest extends FormRequest
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
        $classInfo = ClassInfo::findOrFail($this->class_info_id);
        RosterServices::checkCreateRosterAuthorization($classInfo,$user,$this->my_teacher_id);


        $assignmentCountModuleClass = PlanConstraintsManagementFactory::createRosterCountModule($user,$this->my_teacher_id);
        $assignmentCountModuleClass->check();

        /*if(isset($this->my_teacher_id)){
            list(,$teacher) = UserServices::getAccountTypeAndObject($user);
            $school = School::with('User')->findOrFail($teacher->school_id);
            $assignmentCountModuleClass = RosterCountModuleClass::createByOther($school->User,$school);
            $assignmentCountModuleClass->checkWithCustomizedErrorForTeacher();

        }else{
            $assignmentCountModuleClass = RosterCountModuleClass::createByOwner($user);
            $assignmentCountModuleClass->check();
        }*/

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
//            'class_info_id' => 'required|exists:class_infos,id',
            'class_info_id' => 'required|exists:'.(new ClassInfo())->getTable().',id',
            'name' => 'required|string',
            'description' => 'required|string',
            'color' => 'required|string',

//            'my_teacher_id' => 'nullable|exists:teachers,id',
            'my_teacher_id' => 'nullable|exists:'.(new Teacher())->getTable().',id',

        ];
    }
}
