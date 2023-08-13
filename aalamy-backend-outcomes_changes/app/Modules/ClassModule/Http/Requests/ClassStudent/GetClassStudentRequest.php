<?php

namespace Modules\ClassModule\Http\Requests\ClassStudent;

use App\Exceptions\ErrorUnAuthorizationException;
use App\Http\Controllers\Classes\RequestServicesClass;
use App\Http\Traits\AuthorizesAfterValidation;
use App\Http\Traits\ResponseValidationFormRequest;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\DB;
use Modules\ClassModule\Http\Controllers\Classes\ClassServices;
use Modules\ClassModule\Http\Controllers\Classes\ManageClass\ClassManagementFactory;
use Modules\ClassModule\Models\ClassModel;
use Modules\ClassModule\Traits\ValidationAttributesTrans;
use Modules\User\Http\Controllers\Classes\UserServices;
use Modules\User\Models\Teacher;

class GetClassStudentRequest extends FormRequest
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
//        UserServices::checkRoles($user,config('ClassModule.panel.owners_class_type'));
        UserServices::checkRoles($user,['educator','school','student']);
        $classManagement = ClassManagementFactory::create($user);
        $class = $classManagement->myClassesById($this->route('class_id'));
//        $class = ClassModel::findOrFail($this->route('class_id'));
//        ClassServices::checkGetStudentFromClassAuthorization($user,$class,$this->my_teacher_id);

        if(is_null($class))
            throw new ErrorUnAuthorizationException();

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
//            'my_teacher_id' => 'nullable|exists:teachers,id',
            'my_teacher_id' => 'nullable|exists:'.(new Teacher())->getTable().',id',
        ];
    }
}
