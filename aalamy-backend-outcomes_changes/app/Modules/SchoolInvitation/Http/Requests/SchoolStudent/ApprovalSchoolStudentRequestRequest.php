<?php

namespace Modules\SchoolInvitation\Http\Requests\SchoolStudent;

use App\Http\Traits\AuthorizesAfterValidation;
use App\Http\Traits\ResponseValidationFormRequest;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Modules\SchoolInvitation\Http\Controllers\Classes\SchoolRequestServices;
use Modules\SchoolInvitation\Models\SchoolStudentRequest;
use Modules\SchoolInvitation\Traits\ValidationAttributesTrans;
use Modules\TeacherPermission\Http\Controllers\Classes\PermissionConstraints\StudentPermissionClass;
use Modules\User\Http\Controllers\Classes\UserServices;

class ApprovalSchoolStudentRequestRequest extends FormRequest
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

    protected $schoolStudentRequest;
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
        UserServices::checkRoles($user,['school','student','teacher']);
        $studentRequest = SchoolStudentRequest::findOrFail($this->route('requestId'));

        SchoolRequestServices::checkSchoolStudentRequestApprovalAuthorization($studentRequest,$user,$this->status);
        SchoolRequestServices::RequestIsAvailable($studentRequest);



        $this->setSchoolStudentRequest($studentRequest);
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
            'status' => Rule::in(config('SchoolInvitation.panel.student_request_statuses')),
            'reject_cause' => 'nullable|string',

        ];
    }

    public function getSchoolStudentRequest(){
        return $this->schoolStudentRequest;
    }

    public function setSchoolStudentRequest(SchoolStudentRequest $schoolStudentRequest){
        $this->schoolStudentRequest = $schoolStudentRequest;
    }
}
