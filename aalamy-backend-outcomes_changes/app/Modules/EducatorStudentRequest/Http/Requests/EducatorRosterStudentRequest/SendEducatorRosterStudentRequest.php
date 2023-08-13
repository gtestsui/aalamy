<?php

namespace Modules\EducatorStudentRequest\Http\Requests\EducatorRosterStudentRequest;

use App\Exceptions\ErrorUnAuthorizationException;
use App\Http\Controllers\Classes\RequestServicesClass;
use App\Http\Traits\AuthorizesAfterValidation;
use App\Http\Traits\ResponseValidationFormRequest;
use Illuminate\Foundation\Http\FormRequest;
use Modules\Roster\Http\Controllers\Classes\RosterServices;
use Modules\Roster\Models\Roster;
use Modules\EducatorStudentRequest\Traits\ValidationAttributesTrans;
use Modules\SubscriptionPlan\Http\Controllers\Classes\PlanConstraints\StudentCountModuleClass;
use Modules\User\Http\Controllers\Classes\UserServices;
use Modules\User\Models\Student;

class SendEducatorRosterStudentRequest extends FormRequest
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

    protected Roster $roster;
    /**
     * Customized authorization from AuthorizesAfterValidation Trait
     * to check authorize after validation
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorizeAfterValidate()
    {
        //just the educator who can make this action
        $user = $this->user();
        UserServices::checkRoles($user,['educator']);
        if(isset($this->my_teacher_id))
            throw new ErrorUnAuthorizationException();
        $roster = Roster::findOrFail($this->route('roster_id'));
        RosterServices::checkAddStudentToRosterAuthorization($roster,$user,$this->my_teacher_id);

        $studentCountModuleClass = StudentCountModuleClass::createByOwner($user);
        $studentCountModuleClass->check();

        $this->setRoster($roster);
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
            'student_ids' => 'required|array',
//            'student_ids.*' => 'required|exists:students,id',
            'student_ids.*' => 'required|exists:'.(new Student())->getTable().',id',
            'introductory_message' => 'nullable|string',


        ];
    }

    public function setRoster(Roster $roster){
        $this->roster = $roster;
    }

    public function getRoster(){
        return $this->roster;
    }
}
