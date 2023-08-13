<?php

namespace Modules\RosterAssignment\Http\Requests\RosterAssignment;

use App\Exceptions\ErrorUnAuthorizationException;
use App\Http\Traits\AuthorizesAfterValidation;
use App\Http\Traits\ResponseValidationFormRequest;
use Illuminate\Foundation\Http\FormRequest;
use Modules\RosterAssignment\Http\Controllers\Classes\ManageRosterAssignment\RosterAssignmentManagementFactory;
use Modules\RosterAssignment\Http\Controllers\Classes\ManageRosterAssignment\StudentRosterAssignment;
use Modules\RosterAssignment\Models\RosterAssignment;
use Modules\RosterAssignment\Traits\ValidationAttributesTrans;
use Modules\User\Http\Controllers\Classes\ManageStudent\StudentParentClass;
use Modules\User\Http\Controllers\Classes\UserServices;
use Modules\User\Models\Student;
use Modules\User\Models\Teacher;

class ShowRosterAssignmentForParentRequest extends FormRequest
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
        $user->load('Parent');
        UserServices::checkRoles($user,['parent']);

        $studentParentClass = new StudentParentClass($user->Parent);
        $parentStudent = $studentParentClass->myStudentByStudentId($this->route('student_id'));
        if(is_null($parentStudent))
            throw new ErrorUnAuthorizationException();




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

}
