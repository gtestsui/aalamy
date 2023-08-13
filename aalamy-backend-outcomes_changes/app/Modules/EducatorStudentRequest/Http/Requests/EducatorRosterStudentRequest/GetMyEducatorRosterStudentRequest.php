<?php

namespace Modules\EducatorStudentRequest\Http\Requests\EducatorRosterStudentRequest;

use App\Exceptions\ErrorMsgException;
use App\Http\Traits\AuthorizesAfterValidation;
use App\Http\Traits\ResponseValidationFormRequest;
use Illuminate\Foundation\Http\FormRequest;
use Modules\EducatorStudentRequest\Models\EducatorRosterStudentRequest;
use Modules\EducatorStudentRequest\Traits\ValidationAttributesTrans;
use Modules\User\Http\Controllers\Classes\UserServices;

class GetMyEducatorRosterStudentRequest extends FormRequest
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
        UserServices::checkRoles($user,['student','educator']);

        if(!in_array($this->route('requestType'),config('EducatorStudentRequest.panel.request_types')))
            throw new ErrorMsgException('request type should be received or sent');



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

}
