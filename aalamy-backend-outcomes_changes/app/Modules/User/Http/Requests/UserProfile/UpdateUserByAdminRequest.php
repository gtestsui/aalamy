<?php

namespace Modules\User\Http\Requests\UserProfile;

use App\Http\Controllers\Classes\ApiResponseClass;
use App\Http\Controllers\Classes\RequestServicesClass;
use App\Http\Traits\AuthorizesAfterValidation;
use App\Http\Traits\ResponseValidationFormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Validation\Rule;
use Modules\User\Http\Controllers\Classes\UserServices;
use Modules\User\Models\User;
use Modules\User\Traits\ValidationAttributesTrans;

class UpdateUserByAdminRequest extends UpdateUserPersonalInfoRequest
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


    public function rules()
    {
        $user = $this->user();
        $targetUser = User::findOrFail(
            $this->route('userId')
        );


        $validationRulesByType = $this->additionalRulesByAccountType($targetUser);

        //user validation rules
        $userValidationRules = $this->myRules($targetUser);

        return  array_merge($userValidationRules,$validationRulesByType);
    }

    public function myRules($targetUser)
    {
        Parent::$targetUser = $targetUser;
        //because the admin can update the email
        return array_merge([
            'email' => 'required|unique:users,email,'.$targetUser->id,
            'password' => 'nullable',
        ],Parent::rules());
    }

    /**
     * get the rules from another validation by account type
     * like(EducatorValidation,SchoolValidation,...)
     */
    public function additionalRulesByAccountType(User $user){
        $this->formRequestValidationByType = UserServices::getUpdateProfileValidationRequestByType($user->account_type);
        return  $this->formRequestValidationByType->rules($user);
    }



}
