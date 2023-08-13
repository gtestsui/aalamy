<?php

namespace Modules\User\Http\Requests\UserProfile;

use App\Http\Controllers\Classes\ApiResponseClass;
use App\Http\Traits\AuthorizesAfterValidation;
use App\Http\Traits\ResponseValidationFormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Validation\Rule;
use Modules\Address\Models\Country;
use Modules\Address\Models\State;
use Modules\User\Http\Controllers\Classes\UserServices;
use Modules\User\Models\User;
use Modules\User\Traits\ValidationAttributesTrans;

class UpdateUserPersonalInfoRequest extends FormRequest
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
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    protected static $targetUser=null;

    public function rules()
    {
        if(is_null(Self::$targetUser))
            $user = $this->user();
        else
            $user = Self::$targetUser;
        return  [
            'fname'    =>'required',
            'lname'    =>'required',
            'gender'   =>['required',Rule::in(config('User.panel.genders'))],
            'image'   =>'nullable|string',//base64
            'date_of_birth'   =>'required|date_format:'.config('panel.date_format'),

            'phone_number' => 'nullable|unique:users,phone_number,'.$user->id,
            'phone_code' => 'required_with:phone_number|nullable',
            'phone_iso_code' => 'required_with:phone_code|nullable',

//            'country_id' => 'nullable|exists:countries,id',
            'country_id' => 'nullable|exists:'.(new Country())->getTable().',id',
//            'state_id' => 'required_with:country_id|exists:states,id',
            'state_id' => 'required_with:country_id|exists:'.(new State())->getTable().',id',
            'city' => 'nullable|string',
            'street' => 'nullable|string',

        ];
    }


}
