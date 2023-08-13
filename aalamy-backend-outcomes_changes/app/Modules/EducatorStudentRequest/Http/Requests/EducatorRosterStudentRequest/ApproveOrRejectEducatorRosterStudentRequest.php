<?php

namespace Modules\EducatorStudentRequest\Http\Requests\EducatorRosterStudentRequest;

use App\Exceptions\ErrorUnAuthorizationException;
use App\Http\Controllers\Classes\RequestServicesClass;
use App\Http\Traits\AuthorizesAfterValidation;
use App\Http\Traits\ResponseValidationFormRequest;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Modules\EducatorStudentRequest\Http\Controllers\Classes\EducatorStudentRequestServices;
use Modules\EducatorStudentRequest\Models\EducatorRosterStudentRequest;
use Modules\EducatorStudentRequest\Traits\ValidationAttributesTrans;
use Modules\User\Http\Controllers\Classes\UserServices;

class ApproveOrRejectEducatorRosterStudentRequest extends FormRequest
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

    protected EducatorRosterStudentRequest $educatorRosterStudentRequest;
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
        UserServices::checkRoles($user,['student']);

        $educatorRosterStudentRequest = EducatorRosterStudentRequest::findOrFail($this->route('educator_request_roster_id'));
        if($educatorRosterStudentRequest->student_id != $user->Student->id)
            throw new ErrorUnAuthorizationException();

        EducatorStudentRequestServices::RequestIsAvailable($educatorRosterStudentRequest);

        $this->setEducatorRosterStudentRequest($educatorRosterStudentRequest);
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
            'status' => ['required',Rule::in(config('EducatorStudentRequest.panel.educator_roster_student_request_statuses'))],
            'reject_cause' => 'nullable|string',
        ];
    }

    public function setEducatorRosterStudentRequest(EducatorRosterStudentRequest $educatorRosterStudentRequest){
        $this->educatorRosterStudentRequest = $educatorRosterStudentRequest;
    }

    public function getEducatorRosterStudentRequest(){
        return $this->educatorRosterStudentRequest;
    }
}
