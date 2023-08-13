<?php

namespace Modules\SchoolInvitation\Http\Requests\SchoolTeacher;

use App\Exceptions\ErrorUnAuthorizationException;
use App\Http\Traits\AuthorizesAfterValidation;
use App\Http\Traits\ResponseValidationFormRequest;
use Illuminate\Foundation\Http\FormRequest;
use Modules\SchoolInvitation\Http\Controllers\Classes\SchoolRequestServices;
use Modules\SchoolInvitation\Traits\ValidationAttributesTrans;
use Modules\User\Http\Controllers\Classes\UserServices;
use Modules\User\Models\Educator;
use Modules\User\Models\School;

class SendSchoolTeacherRequestRequest extends FormRequest
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
        UserServices::checkRoles($user,['school','educator']);
        SchoolRequestServices::checkSendSchoolTeacherRequestAuthorization($user,$this->educator_id,$this->school_id);
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
//        if(isset($this->educator_id) && isset($this->school_id))
//            throw new
        return [
//            'educator_id' => 'required_without:school_id|exists:educators,id',
            'educator_id' => 'required_without:school_id|exists:'.(new Educator())->getTable().',id',
//            'school_id' => 'required_without:educator_id|exists:schools,id',
            'school_id' => 'required_without:educator_id|exists:'.(new School())->getTable().',id',
            'introductory_message' => 'nullable|string',
//            'reject_cause' => 'nullable|string',
        ];
    }
}
