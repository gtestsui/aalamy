<?php

namespace Modules\WorkSchedule\Http\Requests\WorkScheduleClass;

use App\Exceptions\ErrorUnAuthorizationException;
use App\Http\Traits\AuthorizesAfterValidation;
use App\Http\Traits\ResponseValidationFormRequest;
use Illuminate\Foundation\Http\FormRequest;
use Modules\ClassModule\Http\Controllers\Classes\ManageClass\ClassManagementFactory;
use Modules\ClassModule\Models\ClassInfo;
use Modules\Roster\Traits\ValidationAttributesTrans;
use Modules\User\Http\Controllers\Classes\UserServices;

class GetWorkScheduleClassByClassIdForCreatorRequest extends FormRequest
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
        UserServices::checkRoles($user,['school']);

        $classManagement = ClassManagementFactory::create($user);
        $classModel = $classManagement->myClassesByIdOrFail($this->class_id);


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


}
