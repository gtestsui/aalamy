<?php

namespace Modules\User\Http\Requests\School;

use App\Exceptions\ErrorUnAuthorizationException;
use App\Http\Traits\AuthorizesAfterValidation;
use App\Http\Traits\ResponseValidationFormRequest;
use Illuminate\Foundation\Http\FormRequest;
use Modules\User\Http\Controllers\Classes\UserServices;
use Modules\User\Models\SchoolStudent;
use Modules\User\Traits\ValidationAttributesTrans;

class GetMySchoolStudentBySchoolStudentIdRequest extends FormRequest
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


    private SchoolStudent $schoolStudent;

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
        UserServices::checkRoles($user,['school']);
        list($accountType,$school) = UserServices::getAccountTypeAndObject($user);
        $schoolStudent = SchoolStudent::findOrFail(
            $this->route('school_student_id')
        );
        if($schoolStudent->school_id != $school->id){
            throw new ErrorUnAuthorizationException();
        }
        $this->setSchoolStudent($schoolStudent);
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


    public function setSchoolStudent(SchoolStudent $schoolStudent){
        $this->schoolStudent = $schoolStudent;
    }

    public function getSchoolStudent(){
        return $this->schoolStudent;
    }


}
