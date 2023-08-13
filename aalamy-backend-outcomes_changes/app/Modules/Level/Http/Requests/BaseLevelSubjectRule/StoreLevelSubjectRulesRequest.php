<?php

namespace Modules\Level\Http\Requests\BaseLevelSubjectRule;

use App\Http\Traits\AuthorizesAfterValidation;
use App\Http\Traits\ResponseValidationFormRequest;
use Illuminate\Foundation\Http\FormRequest;
use Modules\Level\Traits\ValidationAttributesTrans;
use Modules\User\Http\Controllers\Classes\UserServices;

class StoreLevelSubjectRulesRequest extends FormRequest
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
            'base_level_subject_id' => 'required',
            'requires_failure' => 'required|boolean',
            'enter_the_overall_total' => 'required|boolean',
            'optional' => 'required|boolean',
            'max_degree' => 'required|numeric',
            'min_degree' => 'required|numeric',
            'failure_point' => 'required|numeric',
            'its_one_field' => 'required|boolean',
            'classes_count_at_week' => 'required|numeric',
        ];
    }
}
