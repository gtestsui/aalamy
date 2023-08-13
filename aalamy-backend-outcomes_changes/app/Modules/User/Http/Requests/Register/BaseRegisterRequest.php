<?php

namespace Modules\User\Http\Requests\Register;

use App\Http\Traits\AuthorizesAfterValidation;
use App\Http\Traits\ResponseValidationFormRequest;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Modules\Address\Models\Country;
use Modules\Address\Models\State;
use Modules\User\Http\Controllers\Classes\Services\RegisterServices;
use Modules\User\Http\Controllers\Classes\UserServices;
use Modules\User\Traits\ValidationAttributesTrans;

class BaseRegisterRequest extends FormRequest
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


    protected $accountType/*,$accountTypes*/;
    protected FormRequest $formRequestValidationByType;
    public function __construct()
    {
       /* $this->accountTypes = config('User.panel.register_account_types');*/

    }
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

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {

       return [
            'fname'    => 'required',
            'lname'    => 'required',
            'email'    => 'required|email|unique:users,email',
            'image'   =>'nullable|string',//base64
            'date_of_birth'   => 'required|date_format:'.config('panel.date_format'),
            'gender'   =>['required',Rule::in(config('User.panel.genders'))],
            'phone_number' => 'required_without:email|nullable|unique:users,phone_number',
            'phone_code' => 'required_with:phone_number|nullable',
            'phone_iso_code' => 'required_with:phone_iso_code|nullable',
            'device_type' => ['required',Rule::in(config('User.panel.user_device_types'))],


//            'country_id' => 'nullable|exists:countries,id',
            'country_id' => 'nullable|exists:'.(new Country())->getTable().',id',
//            'state_id' => 'required_with:country_id|exists:states,id',
            'state_id' => 'required_with:country_id|exists:'.(new State())->getTable().',id',
            'city' => 'nullable|string',
            'street' => 'nullable|string',
            'firebase_token' => 'required'

       ];


//        $validationRulesByType = $this->additionalRulesByAccountType();
//
//        $userValidationRules = $this->myRules();
//
//        return  array_merge($userValidationRules,$validationRulesByType,$sharedRules);
    }



    /*
     * get additional rules by type(educator rules , school rules,...)
     */
    public function additionalRulesByAccountType($accountType){
        $this->accountType = $accountType/*$this->route('accountType')*/;
        $this->formRequestValidationByType = UserServices::getStoreValidationRequestByType($this->accountType);
        return  $this->formRequestValidationByType->rules();
    }





}
