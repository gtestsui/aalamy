<?php

namespace Modules\SchoolInvitation\Http\Requests\SchoolTeacher;

use App\Http\Traits\AuthorizesAfterValidation;
use App\Http\Traits\ResponseValidationFormRequest;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Modules\SchoolInvitation\Http\Controllers\Classes\SchoolRequestServices;
use Modules\SchoolInvitation\Models\SchoolTeacherRequest;
use Modules\SchoolInvitation\Traits\ValidationAttributesTrans;

class ApprovalSchoolTeacherRequestRequest extends FormRequest
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

    protected $schoolTeacherRequest;
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
        $teacherRequest = SchoolTeacherRequest::findOrFail($this->route('requestId'));
        SchoolRequestServices::checkSchoolTeacherRequestApprovalAuthorization($teacherRequest,$user);
        SchoolRequestServices::RequestIsAvailable($teacherRequest);

        $this->setSchoolTeacherRequest($teacherRequest);
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
            'status' => Rule::in(config('SchoolInvitation.panel.teacher_request_statuses')),
            'reject_cause' => 'nullable|string',

        ];
    }

    public function getSchoolTeacherRequest(){
        return $this->schoolTeacherRequest;
    }

    public function setSchoolTeacherRequest(SchoolTeacherRequest $schoolTeacherRequest){
        $this->schoolTeacherRequest = $schoolTeacherRequest;
    }
}
