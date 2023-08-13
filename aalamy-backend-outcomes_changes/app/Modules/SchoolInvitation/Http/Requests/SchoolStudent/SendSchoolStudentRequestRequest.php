<?php

namespace Modules\SchoolInvitation\Http\Requests\SchoolStudent;

use App\Exceptions\ErrorUnAuthorizationException;
use App\Http\Traits\AuthorizesAfterValidation;
use App\Http\Traits\ResponseValidationFormRequest;
use Illuminate\Foundation\Http\FormRequest;
use Modules\SchoolInvitation\Http\Controllers\Classes\SchoolRequestServices;
use Modules\SchoolInvitation\Traits\ValidationAttributesTrans;
use Modules\TeacherPermission\Http\Controllers\Classes\PermissionConstraints\StudentPermissionClass;
use Modules\User\Http\Controllers\Classes\UserServices;
use Modules\User\Models\School;
use Modules\User\Models\Student;

class SendSchoolStudentRequestRequest extends FormRequest
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
        UserServices::checkRoles($user,['student','school','teacher']);

        SchoolRequestServices::checkSendSchoolStudentRequestAuthorization($user,$this->student_id,$this->school_id);


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
//            'student_id' => 'required_without:school_id|exists:students,id',
            'student_id' => 'required_without:school_id|exists:'.(new Student())->getTable().',id',
//            'school_id' => 'required_without:student_id|exists:schools,id',
            'school_id' => 'required_without:student_id|exists:'.(new School())->getTable().',id',
            'introductory_message' => 'nullable|string',
//            'reject_cause' => 'nullable|string',
        ];
    }
}
