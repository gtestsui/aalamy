<?php

namespace Modules\RosterAssignment\Http\Requests\RosterAssignment;

use App\Http\Traits\AuthorizesAfterValidation;
use App\Http\Traits\ResponseValidationFormRequest;
use Illuminate\Foundation\Http\FormRequest;
use Modules\Assignment\Http\Controllers\Classes\ManageAssignment\AssignmentManagementFactory;
use Modules\Assignment\Models\Assignment;
use Modules\RosterAssignment\Traits\ValidationAttributesTrans;
use Modules\User\Http\Controllers\Classes\UserServices;
use Modules\User\Models\Teacher;

class GetByAssignmentIdRequest extends FormRequest
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

    private Assignment $assignment;
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
        $assignmentClass = AssignmentManagementFactory::create($user);
        $assignment = $assignmentClass->myAssignmentByIdOrFail($this->assignment_id);

//        $this->setAssignment($assignment);
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
//            'assignment_id' => 'required|exists:assignments,id',
            'assignment_id' => 'required|exists:'.(new Assignment())->getTable().',id',

//            'my_teacher_id' => 'nullable|exists:teachers,id',
            'my_teacher_id' => 'nullable|exists:'.(new Teacher())->getTable().',id',

        ];
    }

    public function setAssignment(Assignment $assignment){
        $this->assignment = $assignment;
    }

    public function getAssignment(){
        return $this->assignment;
    }
}
