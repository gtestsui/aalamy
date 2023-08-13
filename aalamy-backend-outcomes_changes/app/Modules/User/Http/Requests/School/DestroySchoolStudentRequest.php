<?php

namespace Modules\User\Http\Requests\School;

use App\Exceptions\ErrorUnAuthorizationException;
use App\Http\Controllers\Classes\RequestServicesClass;
use App\Http\Traits\AuthorizesAfterValidation;
use App\Http\Traits\ResponseValidationFormRequest;
use Illuminate\Foundation\Http\FormRequest;
use Modules\TeacherPermission\Http\Controllers\Classes\PermissionConstraints\StudentPermissionClass;
use Modules\User\Http\Controllers\Classes\UserServices;
use Modules\User\Models\SchoolStudent;
use Modules\User\Traits\ValidationAttributesTrans;

class DestroySchoolStudentRequest extends FormRequest
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


    protected SchoolStudent $schoolStudent;

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
        UserServices::checkRoles($user,['school','student','educator']);

        $schoolStudent = SchoolStudent::findOrFail($this->route('school_student_id'));


        if(isset($request->my_teacher_id)){
            list(,$teacher) = UserServices::getAccountTypeAndObject($user);
//            $teacher = Teacher::findOrFail($request->my_teacher_id);
            $unitPermissionClass = new StudentPermissionClass($teacher);
            $unitPermissionClass->checkIfHavePermission('delete')
                ->checkDelete($schoolStudent);

            return true;
        }else{
            /**
             * check if the user who trying to delete
             * either the same school or the same student
             */
            $user->load(ucfirst($user->account_type));
            if( $schoolStudent->{$user->account_type.'_id'} !=  $user->{ucfirst($user->account_type)}->id)
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
