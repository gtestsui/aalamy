<?php

namespace Modules\RosterAssignment\Http\Requests\RosterAssignment;

use App\Http\Traits\AuthorizesAfterValidation;
use App\Http\Traits\ResponseValidationFormRequest;
use Illuminate\Foundation\Http\FormRequest;
use Modules\Assignment\Http\Controllers\Classes\AssignmentServices;
use Modules\Assignment\Models\Assignment;
use Modules\RosterAssignment\Models\RosterAssignment;
use Modules\RosterAssignment\Traits\ValidationAttributesTrans;
use Modules\User\Http\Controllers\Classes\UserServices;
use Modules\User\Models\Teacher;

class DestroyRosterAssignmentRequest extends FormRequest
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

    private RosterAssignment $rosterAssignment;
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

        $rosterAssignment = RosterAssignment::findOrFail($this->route('id'));
        $assignment = Assignment::findOrFail($rosterAssignment->assignment_id);


        AssignmentServices::checkAddRosterAssignmentAuthorization($assignment,$user,$this->my_teacher_id);
        $this->setRosterAssignment($rosterAssignment);
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

//            'my_teacher_id' => 'nullable|exists:teachers,id',
            'my_teacher_id' => 'nullable|exists:'.(new Teacher())->getTable().',id',

        ];
    }

    public function setRosterAssignment(RosterAssignment $rosterAssignment){
        $this->rosterAssignment = $rosterAssignment;
    }

    public function getRosterAssignment(){
        return $this->rosterAssignment;
    }
}
