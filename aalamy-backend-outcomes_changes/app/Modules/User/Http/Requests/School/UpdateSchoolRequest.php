<?php

namespace Modules\User\Http\Requests\School;

use App\Http\Controllers\Classes\ApiResponseClass;
use App\Http\Traits\AuthorizesAfterValidation;
use App\Http\Traits\ResponseValidationFormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Modules\Address\Models\Country;
use Modules\Address\Models\State;
use Modules\User\Models\User;
use Modules\User\Traits\ValidationAttributesTrans;

class UpdateSchoolRequest extends FormRequest
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

    public function rules()
    {
        return [
            'school_name' => 'required|min:3',
            'school_image' => 'nullable|string',//base64
            'bio' => 'nullable',
            'allow_student_request' => 'boolean',
            'allow_teacher_request' => 'boolean',
//            'school_country_id' => 'nullable|exists:countries,id',
            'school_country_id' => 'nullable|exists:'.(new Country())->getTable().',id',
//            'school_state_id' => 'required_with:country_id|exists:states,id',
            'school_state_id' => 'required_with:country_id|exists:'.(new State())->getTable().',id',
//            'school_city_id' => 'nullable|exists:cities,id',
            'school_city' => 'nullable|string',
            'school_street' => 'nullable|string',
        ];
    }

}
