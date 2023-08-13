<?php

namespace Modules\Assignment\Http\Requests\Page;

use App\Exceptions\ErrorUnAuthorizationException;
use App\Http\Traits\AuthorizesAfterValidation;
use App\Http\Traits\ResponseValidationFormRequest;
use Illuminate\Foundation\Http\FormRequest;
use Modules\Assignment\Http\Controllers\Classes\AssignmentServices;
use Modules\Assignment\Http\Controllers\Classes\ManageAssignment\AssignmentManagementFactory;
use Modules\Assignment\Http\Controllers\Classes\ManageAssignmentPage\AssignmentPageManagementFactory;
use Modules\Assignment\Models\Page;
use Modules\Assignment\Traits\ValidationAttributesTrans;
use Modules\Level\Http\Controllers\Classes\LevelServices;
use Modules\Level\Models\Lesson;
use Modules\Level\Models\LevelSubject;
use Modules\Level\Models\Unit;
use Modules\User\Http\Controllers\Classes\UserServices;
use Modules\User\Models\Teacher;

class ShowPageRequest extends FormRequest
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
        UserServices::checkRoles($user,['educator','school'/*,'student'*/]);
        $assignmentPageClass = AssignmentPageManagementFactory::create($user,$this->my_teacher_id);
        $page = $assignmentPageClass->getMyAssignmentPageByAssignmentIdByPageId(
            $this->route('assignment_id'),$this->route('page_id')
        );
        if(is_null($page))
            throw new ErrorUnAuthorizationException();

        $this->setPage($page);


//        $page = Page::findOrFail($this->route('id'));
//        $assignmentClass = AssignmentManagementFactory::create($user,$this->my_teacher_id);
//        $assignment = $assignmentClass->myAssignmentById($page->assignment_id);
//        if(is_null($assignment))
//            throw new ErrorUnAuthorizationException();

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
