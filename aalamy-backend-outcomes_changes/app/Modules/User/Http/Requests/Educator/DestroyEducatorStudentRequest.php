<?php

namespace Modules\User\Http\Requests\Educator;

use App\Exceptions\ErrorUnAuthorizationException;
use App\Http\Controllers\Classes\ApiResponseClass;
use App\Http\Controllers\Classes\RequestServicesClass;
use App\Http\Traits\AuthorizesAfterValidation;
use App\Http\Traits\ResponseValidationFormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Modules\User\Http\Controllers\Classes\UserServices;
use Modules\User\Models\EducatorStudent;
use Modules\User\Models\SchoolStudent;
use Modules\User\Models\User;
use Modules\User\Traits\ValidationAttributesTrans;

class DestroyEducatorStudentRequest extends FormRequest
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


    protected EducatorStudent $educatorStudent;

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
        UserServices::checkRoles($user,['educator','student']);
        $educatorStudent = EducatorStudent::findOrFail($this->route('educator_student_id'));

        /**
         * check if the user who trying to delete
         * ether the same school or the same student
         */
        $user->load(ucfirst($user->account_type));
        if( $educatorStudent->{$user->account_type.'_id'} !=  $user->{ucfirst($user->account_type)}->id)
            throw new ErrorUnAuthorizationException();

        $this->setEducatorStudent($educatorStudent);

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

    public function setEducatorStudent(EducatorStudent $educatorStudent){
        $this->educatorStudent = $educatorStudent;
    }

    public function getEducatorStudent(){
        return $this->educatorStudent;
    }

}
