<?php

namespace Modules\User\Http\Requests\ParentStudent;

use App\Exceptions\ErrorUnAuthorizationException;
use App\Http\Controllers\Classes\RequestServicesClass;
use App\Http\Traits\AuthorizesAfterValidation;
use App\Http\Traits\ResponseValidationFormRequest;
use Illuminate\Foundation\Http\FormRequest;
use Modules\User\Http\Controllers\Classes\ManageStudent\StudentManagementFactory;
use Modules\User\Http\Controllers\Classes\UserServices;
use Modules\User\Models\Student;
use Modules\User\Models\Teacher;
use Modules\User\Traits\ValidationAttributesTrans;

class SendParentStudentLinkRequest extends FormRequest
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


    protected Student $student;

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
        UserServices::checkRoles($user,['school','educator']);

        $student = Student::with('User')
            ->findOrFail($this->route('student_id'));

        //here check if this student belongs to this user
//        $studentManagementClass = UserServices::createClassStudentManagementClassByType($user->account_type,$user,$this->my_teacher_id);
        list($accountType,$accountObject) = UserServices::getAccountTypeAndObject($user,$this->my_teacher_id);
        $studentManagementClass = StudentManagementFactory::createByAccountTypeAndObject($accountType,$accountObject);
        $myStudentById = $studentManagementClass->myStudentByStudentId($student->id);
        if(is_null($myStudentById))
            throw new ErrorUnAuthorizationException();

        $this->setStudent($student);
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
            'email' => 'required|email',
//            'my_teacher_id' => 'nullable|exists:teachers,id',
            'my_teacher_id' => 'nullable|exists:'.(new Teacher())->getTable().',id',
        ];
    }

    public function setStudent(Student $student){
        $this->student = $student;
    }

    public function getStudent(){
        return $this->student;
    }

}
