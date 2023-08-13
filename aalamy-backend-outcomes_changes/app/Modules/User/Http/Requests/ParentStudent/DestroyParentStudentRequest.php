<?php

namespace Modules\User\Http\Requests\ParentStudent;

use App\Exceptions\ErrorUnAuthorizationException;
use App\Http\Controllers\Classes\ApiResponseClass;
use App\Http\Controllers\Classes\RequestServicesClass;
use App\Http\Traits\AuthorizesAfterValidation;
use App\Http\Traits\ResponseValidationFormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Modules\User\Http\Controllers\Classes\UserServices;
use Modules\User\Models\ParentStudent;
use Modules\User\Traits\ValidationAttributesTrans;

class DestroyParentStudentRequest extends FormRequest
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
    private ParentStudent $parentStudent;
    public function authorizeAfterValidate()
    {
        $user = $this->user();
        UserServices::checkRoles($user,['parent']);
        $user->load('Parent');
        $parentStudent = ParentStudent::findOrFail($this->route('parent_student_id'));
        if($user->Parent->id != $parentStudent->parent_id)
            throw new ErrorUnAuthorizationException();
        $this->setParentStudent($parentStudent);
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
//            'parent_codes' => 'required|array',
//            'parent_codes.*' => 'required',
        ];
    }


    public function setParentStudent(ParentStudent $parentStudent){
        $this->parentStudent = $parentStudent;
    }

    public function getParentStudent(){
        return $this->parentStudent;
    }

}
