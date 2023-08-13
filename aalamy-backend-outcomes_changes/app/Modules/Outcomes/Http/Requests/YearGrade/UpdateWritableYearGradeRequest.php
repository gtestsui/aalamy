<?php

namespace Modules\Outcomes\Http\Requests\YearGrade;

use App\Http\Traits\AuthorizesAfterValidation;
use App\Http\Traits\ResponseValidationFormRequest;
use Illuminate\Foundation\Http\FormRequest;
use Modules\Level\Traits\ValidationAttributesTrans;

class UpdateWritableYearGradeRequest extends FormRequest
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
            'exam_semester_1' => 'nullable|numeric',
            'exam_semester_2' => 'nullable|numeric',
            'work_degree_semester_1' => 'nullable|numeric',
            'work_degree_semester_2' => 'nullable|numeric',
            'total_semester_1' => 'nullable|numeric',
            'total_semester_2' => 'nullable|numeric',
        ];
    }



}
