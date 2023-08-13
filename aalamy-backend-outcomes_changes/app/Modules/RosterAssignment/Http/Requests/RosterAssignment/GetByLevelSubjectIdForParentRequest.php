<?php

namespace Modules\RosterAssignment\Http\Requests\RosterAssignment;

use App\Exceptions\ErrorUnAuthorizationException;
use App\Http\Traits\AuthorizesAfterValidation;
use App\Http\Traits\ResponseValidationFormRequest;
use Illuminate\Foundation\Http\FormRequest;
use Modules\RosterAssignment\Traits\ValidationAttributesTrans;
use Modules\Roster\Http\Controllers\Classes\ManageRoster\RosterManagementFactory;
use Modules\Roster\Models\Roster;
use Modules\User\Http\Controllers\Classes\ManageStudent\StudentParentClass;
use Modules\User\Http\Controllers\Classes\UserServices;
use Modules\User\Models\Teacher;

class GetByLevelSubjectIdForParentRequest extends FormRequest
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

        $parentStudentClass = new StudentParentClass($user->Parent);
        $student = $parentStudentClass->myStudentByStudentId($this->route('student_id'));
        if(is_null($student))
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
            'level_subject_id' => 'required',


        ];
    }

}
