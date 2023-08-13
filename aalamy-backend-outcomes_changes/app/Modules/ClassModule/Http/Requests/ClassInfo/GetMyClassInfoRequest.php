<?php

namespace Modules\ClassModule\Http\Requests\ClassInfo;


use App\Exceptions\ErrorMsgException;
use App\Http\Controllers\Classes\ApplicationModules;
use App\Http\Traits\AuthorizesAfterValidation;
use App\Http\Traits\ResponseValidationFormRequest;
use Modules\ClassModule\Http\Controllers\Classes\ClassServices;
use Modules\ClassModule\Models\ClassInfo;
use Modules\ClassModule\Models\ClassModel;
use Modules\ClassModule\Traits\ValidationAttributesTrans;
use Modules\Level\Http\Controllers\Classes\LevelServices;
use Modules\Level\Models\LevelSubject;
use Modules\User\Http\Controllers\Classes\UserServices;
use Modules\User\Models\Teacher;

class GetMyClassInfoRequest extends BaseClassInfoRequest
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
        UserServices::checkRoles($user,['educator','school']);

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
