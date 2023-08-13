<?php

namespace Modules\User\Http\Requests\UserProfile;

use App\Exceptions\ErrorUnAuthorizationException;
use App\Http\Controllers\Classes\ApiResponseClass;
use App\Http\Controllers\Classes\RequestServicesClass;
use App\Http\Traits\AuthorizesAfterValidation;
use App\Http\Traits\ResponseValidationFormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Validation\Rule;
use Modules\User\Http\Controllers\Classes\ManageStudent\StudentSchoolClass;
use Modules\User\Http\Controllers\Classes\UserServices;
use Modules\User\Http\Requests\Student\UpdateStudentRequest;
use Modules\User\Models\SchoolStudent;
use Modules\User\Models\Student;
use Modules\User\Models\User;
use Modules\User\Traits\ValidationAttributesTrans;

class UpdateStudentBySchoolRequest extends UpdateUserPersonalInfoRequest
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

    private User $studentUser;
    private Student $targetStudent;
    public function __construct()
    {
        $request = request();
        $schoolStudent = SchoolStudent::findOrFail(
            $request->route('school_student_id')
        );

        $targetUser = User::whereHas('Student',function ($query)use($schoolStudent){
                return $query->whereHas('SchoolStudent',function ($query1)use($schoolStudent){
                   return $query1->where('id',$schoolStudent->id);
                });
            })
            ->with('Student')->first();

        $student = Student::with('User')->findOrFail($schoolStudent->student_id);
        $this->targetStudent = $targetUser->Student;
        $this->setTargetUser($targetUser);

//        $student = Student::with('User')->findOrFail($schoolStudent->student_id);
//        $this->targetStudent = $student;
//        $this->setTargetUser($student->User);
    }

    public function authorizeAfterValidate(){
        $user = $this->user();
        UserServices::checkRoles($user,['school']);
        $user->load('School');
        $myStudent = (new StudentSchoolClass($user->School))
            ->myStudentByStudentId($this->targetStudent->id);
        if(is_null($myStudent))
            throw new ErrorUnAuthorizationException();
        return true;
    }

    public function rules()
    {
        $updateStudentValidationClass = new UpdateStudentRequest();
        $validationRulesByType = $updateStudentValidationClass->rules(
            $this->getTargetUser()
        );

        //user validation rules
        $userValidationRules = $this->myRules($this->getTargetUser());

        return  array_merge($userValidationRules,$validationRulesByType);
    }

    public function myRules($targetUser)
    {
        Parent::$targetUser = $targetUser;
        return Parent::rules();
    }

    /**
     * get the rules from another validation by account type
     * like(EducatorValidation,SchoolValidation,...)
     */
    public function additionalRulesByAccountType(User $user){
        $this->formRequestValidationByType = UserServices::getUpdateProfileValidationRequestByType($user->account_type);
        return  $this->formRequestValidationByType->rules($user);
    }


    public function setTargetUser(User $studentUser){
        $this->studentUser = $studentUser;
    }

    public function getTargetUser(){
        return $this->studentUser;
    }



}
