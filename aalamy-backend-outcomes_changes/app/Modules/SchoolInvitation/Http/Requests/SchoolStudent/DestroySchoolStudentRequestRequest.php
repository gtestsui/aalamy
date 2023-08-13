<?php

namespace Modules\SchoolInvitation\Http\Requests\SchoolStudent;

use App\Http\Traits\AuthorizesAfterValidation;
use App\Http\Traits\ResponseValidationFormRequest;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Modules\SchoolInvitation\Http\Controllers\Classes\SchoolRequestServices;
use Modules\SchoolInvitation\Models\SchoolStudentRequest;
use Modules\SchoolInvitation\Traits\ValidationAttributesTrans;

class DestroySchoolStudentRequestRequest extends FormRequest
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
        $studentRequest = SchoolStudentRequest::findOrFail($this->route('requestId'));
        SchoolRequestServices::checkSchoolRequestDestroyAuthorization($studentRequest,$user);
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
        ];
    }

    public function getSchoolStudentRequest(){
        return $this->schoolStudentRequest;
    }

    public function setSchoolStudentRequest(SchoolStudentRequest $schoolStudentRequest){
        $this->schoolStudentRequest = $schoolStudentRequest;
    }
}
