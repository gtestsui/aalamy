<?php

namespace Modules\SchoolEmployee\Http\Requests\SchoolEmployee;

use App\Exceptions\ErrorUnAuthorizationException;
use App\Http\Controllers\Classes\ApplicationModules;
use App\Http\Traits\AuthorizesAfterValidation;
use App\Http\Traits\ResponseValidationFormRequest;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Modules\ClassModule\Http\Controllers\Classes\ManageClass\ClassManagementFactory;
use Modules\ClassModule\Models\ClassModel;
use Modules\Level\Http\Controllers\Classes\ManageSubject\SubjectManagementFactory;
use Modules\Level\Traits\ValidationAttributesTrans;
use Modules\SchoolEmployee\Models\SchoolEmployee;
use Modules\User\Http\Controllers\Classes\UserServices;
use Modules\User\Http\Requests\Register\BaseRegisterRequest;

class DestroySchoolEmployeeRequest extends FormRequest
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


    private SchoolEmployee $schoolEmployee;

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
        $user->load('School');
        $employee = SchoolEmployee::my($user->School->id)
            ->findOrFail($this->route('id'));
        $this->setSchoolEmployee($employee);

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


    public function getSchoolEmployee(){
        return $this->schoolEmployee;
    }

    public function setSchoolEmployee(SchoolEmployee $schoolEmployee){
        $this->schoolEmployee = $schoolEmployee;
    }


}
