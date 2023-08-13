<?php

namespace Modules\Outcomes\Http\Requests\Mark;

use App\Exceptions\ErrorUnAuthorizationException;
use App\Http\Traits\AuthorizesAfterValidation;
use App\Http\Traits\ResponseValidationFormRequest;
use Illuminate\Foundation\Http\FormRequest;
use Modules\ClassModule\Http\Controllers\Classes\ManageClass\ClassManagementFactory;
use Modules\ClassModule\Models\ClassModel;
use Modules\Level\Http\Controllers\Classes\ManageSubject\SubjectManagementFactory;
use Modules\Level\Traits\ValidationAttributesTrans;
use Modules\User\Http\Controllers\Classes\UserServices;

class GetStudentsMarksByClassIdRequest extends FormRequest
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


    protected ClassModel $class;

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
        UserServices::checkRoles($user,['school','teacher']);

        $subjectManagament = SubjectManagementFactory::create($user);
        $subject = $subjectManagament->mySubjectById($this->route('subject_id'));


        $classManagament = ClassManagementFactory::create($user);
        $class = $classManagament->myClassesById($this->route('class_id'));
        if(is_null($class) || is_null($subject)){
            throw new ErrorUnAuthorizationException();
        }
        $this->setClass($class);
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


    public function setClass(ClassModel $class){
        $this->class = $class;
    }

    /**
     * @return ClassModel
     */
    public function getClass(){
        return $this->class;
    }

}
