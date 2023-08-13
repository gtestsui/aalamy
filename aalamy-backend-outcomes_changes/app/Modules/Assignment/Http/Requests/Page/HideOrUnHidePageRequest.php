<?php

namespace Modules\Assignment\Http\Requests\Page;

use App\Exceptions\ErrorUnAuthorizationException;
use App\Http\Traits\AuthorizesAfterValidation;
use App\Http\Traits\ResponseValidationFormRequest;
use Illuminate\Foundation\Http\FormRequest;
use Modules\Assignment\Http\Controllers\Classes\AssignmentServices;
use Modules\Assignment\Http\Controllers\Classes\ManageAssignment\AssignmentManagementFactory;
use Modules\Assignment\Models\Page;
use Modules\Assignment\Traits\ValidationAttributesTrans;
use Modules\Level\Http\Controllers\Classes\LevelServices;
use Modules\Level\Models\Lesson;
use Modules\Level\Models\LevelSubject;
use Modules\Level\Models\Unit;
use Modules\User\Http\Controllers\Classes\UserServices;
use Modules\User\Models\Teacher;

class HideOrUnHidePageRequest extends FormRequest
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

    private Page $page;

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
        $page = Page::findOrFail($this->route('id'));
        $assignmentClass = AssignmentManagementFactory::create($user,$this->my_teacher_id);

        $assignment = $assignmentClass->myAssignmentById($page->assignment_id);
        if(is_null($assignment))
            throw new ErrorUnAuthorizationException();

        $this->setPage($page);

        //if he can delete assignment then he can delete its pages
//        AssignmentServices::checkDeleteAssignmentAuthorization($assignment,$user,$this->my_teacher_id);
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

    public function setPage(Page $page){
        $this->page = $page;
    }

    public function getPage(){
        return $this->page;
    }

}
