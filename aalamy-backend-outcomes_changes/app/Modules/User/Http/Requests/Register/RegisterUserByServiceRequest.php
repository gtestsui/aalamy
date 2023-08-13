<?php

namespace Modules\User\Http\Requests\Register;

use App\Http\Traits\AuthorizesAfterValidation;
use App\Http\Traits\ResponseValidationFormRequest;
use Modules\User\Http\Controllers\Classes\Services\RegisterServices;
use Modules\User\Traits\ValidationAttributesTrans;

class RegisterUserByServiceRequest extends BaseRegisterRequest
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

        RegisterServices::checkServiceSupport($this->route('service'));
        RegisterServices::checkAccountTypeForRegister($this->route('accountType'));
        return true;
    }

    public function rules(){
        $baseRules = Parent::rules();
        $myRules = $this->myRules();
        $accountRules = $this->additionalRulesByAccountType(
            $this->route('accountType')
        );
        return  array_merge($baseRules,$myRules,$accountRules);

    }

    public function myRules(){

        return  [
            'login_service_id' => 'required|unique:users,login_service_id',
            'login_service_avatar' => 'nullable',
            'service_access_token' => 'required',


        ];
    }




}
