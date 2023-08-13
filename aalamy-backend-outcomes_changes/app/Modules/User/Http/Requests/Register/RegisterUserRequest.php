<?php

namespace Modules\User\Http\Requests\Register;

use Modules\User\Http\Controllers\Classes\Services\RegisterServices;

class RegisterUserRequest extends BaseRegisterRequest
{


    /**
     * Customized authorization from AuthorizesAfterValidation Trait
     * to check authorize after validation
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorizeAfterValidate()
    {
//        UserServices::checkAccountTypeForRegister($this->route('accountType'));
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
            'password' =>'required|min:6|confirmed',
            'password_confirmation' =>'required_with:password',

        ];
    }




}
