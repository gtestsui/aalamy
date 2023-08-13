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
use Modules\Assignment\Models\AssignmentFolder;
use Modules\Assignment\Traits\ValidationAttributesTrans;
use Modules\Level\Http\Controllers\Classes\LevelServices;
use Modules\Level\Models\Lesson;
use Modules\Level\Models\LevelSubject;
use Modules\Level\Models\Unit;
use Modules\User\Http\Controllers\Classes\UserServices;
use Modules\User\Models\Teacher;

class UpdateAssignmentFolderRequest extends FormRequest
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

    private AssignmentFolder $assignmentFolder;
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

        $this->setAssignmentFolder($assignmentFolder);
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

            'name' => 'nullable|string',

//            'my_teacher_id' => 'nullable|exists:teachers,id',
            'my_teacher_id' => 'nullable|exists:'.(new Teacher())->getTable().',id',

        ];
    }


    public function setAssignmentFolder(AssignmentFolder $assignmentFolder){
        $this->assignmentFolder = $assignmentFolder;
    }

    public function getAssignmentFolder(){
        return $this->assignmentFolder;
    }


}
