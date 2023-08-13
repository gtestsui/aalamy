<?php

namespace Modules\Assignment\Http\Requests\AssignmentFolder;

use App\Exceptions\ErrorUnAuthorizationException;
use App\Http\Controllers\Classes\RequestServicesClass;
use App\Http\Traits\AuthorizesAfterValidation;
use App\Http\Traits\ResponseValidationFormRequest;
use Illuminate\Foundation\Http\FormRequest;
use Modules\Assignment\Http\Controllers\Classes\AssignmentServices;
use Modules\Assignment\Http\Controllers\Classes\ManageAssignmentFolder\AssignmentFolderManagementFactory;
use Modules\Assignment\Models\Assignment;
use Modules\Assignment\Traits\ValidationAttributesTrans;
use Modules\Level\Http\Controllers\Classes\LevelServices;
use Modules\Roster\Http\Controllers\Classes\ManageRoster\RosterManagementFactory;
use Modules\User\Http\Controllers\Classes\UserServices;
use Modules\User\Models\Teacher;

class GetFolderContentRequest extends FormRequest
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

        $assignmentFolderClass = AssignmentFolderManagementFactory::create($user);
        $assignmentFolder = $assignmentFolderClass->myAssignmentFolderById($this->route('id'));
        if(is_null($assignmentFolder))
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
