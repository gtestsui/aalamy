<?php

namespace Modules\Assignment\Http\Requests\AssignmentFolder;

use App\Exceptions\ErrorMsgException;
use App\Exceptions\ErrorUnAuthorizationException;
use App\Http\Traits\AuthorizesAfterValidation;
use App\Http\Traits\ResponseValidationFormRequest;
use Illuminate\Foundation\Http\FormRequest;
use Modules\Assignment\Http\Controllers\Classes\ManageAssignmentFolder\AssignmentFolderManagementFactory;
use Modules\Assignment\Models\Assignment;
use Modules\Assignment\Traits\ValidationAttributesTrans;
use Modules\User\Http\Controllers\Classes\UserServices;
use Modules\User\Models\Teacher;

class StoreAssignmentFolderRequest extends FormRequest
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

        if(isset($this->assignment_folder_id)){
            $assignmentFolderClass = AssignmentFolderManagementFactory::create($user);
            $assignmentFolder = $assignmentFolderClass->myAssignmentFolderById($this->assignment_folder_id);
            if(is_null($assignmentFolder))
                throw new ErrorUnAuthorizationException();

            $countOfContentAssignments = Assignment::where('assignment_folder_id',$assignmentFolder->id)->count();
            if($countOfContentAssignments)
                throw new ErrorMsgException('this folder have another folders inside it');

        }

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

            'assignment_folder_id' => 'nullable',
            'name' => 'required|string',


//            'my_teacher_id' => 'nullable|exists:teachers,id',
            'my_teacher_id' => 'nullable|exists:'.(new Teacher())->getTable().',id',

        ];
    }
}
