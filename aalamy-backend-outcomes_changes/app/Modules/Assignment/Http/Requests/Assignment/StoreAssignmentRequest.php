<?php

namespace Modules\Assignment\Http\Requests\Assignment;

use App\Exceptions\ErrorMsgException;
use App\Exceptions\ErrorUnAuthorizationException;
use App\Http\Traits\AuthorizesAfterValidation;
use App\Http\Traits\ResponseValidationFormRequest;
use Illuminate\Foundation\Http\FormRequest;
use Modules\Assignment\Http\Controllers\Classes\AssignmentServices;
use Modules\Assignment\Http\Controllers\Classes\ManageAssignmentFolder\AssignmentFolderManagementFactory;
use Modules\Assignment\Models\AssignmentFolder;
use Modules\Assignment\Traits\ValidationAttributesTrans;
use Modules\Level\Http\Controllers\Classes\LevelServices;
use Modules\Level\Models\Lesson;
use Modules\Level\Models\LevelSubject;
use Modules\Level\Models\Unit;
use Modules\SubscriptionPlan\Http\Controllers\Classes\PlanConstraints\AssignmentCountModuleClass;
use Modules\SubscriptionPlan\Http\Controllers\Classes\PlanConstraints\Factory\PlanConstraintsManagementFactory;
use Modules\User\Http\Controllers\Classes\UserServices;
use Modules\User\Models\School;
use Modules\User\Models\Teacher;

class StoreAssignmentRequest extends FormRequest
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

        $assignmentCountModuleClass = PlanConstraintsManagementFactory::createAssignmentCountModule($user,$this->my_teacher_id);
        $assignmentCountModuleClass->check();

        /*if(isset($this->my_teacher_id)){
            list(,$teacher) = UserServices::getAccountTypeAndObject($user);
            $school = School::with('User')->findOrFail($teacher->school_id);
            $assignmentCountModuleClass = AssignmentCountModuleClass::createByOther($school->User,$school);
            $assignmentCountModuleClass->checkWithCustomizedErrorForTeacher();

        }else{
            $assignmentCountModuleClass = AssignmentCountModuleClass::createByOwner($user);
            $assignmentCountModuleClass->check();
        }*/


        $assignmentFolderClass = AssignmentFolderManagementFactory::create($user);
        $assignmentFolder = $assignmentFolderClass->myAssignmentFolderById($this->assignment_folder_id);
        if(is_null($assignmentFolder))
            throw new ErrorUnAuthorizationException();

        $countOfContentFolders = AssignmentFolder::where('parent_id',$assignmentFolder->id)->count();
        if($countOfContentFolders)
            throw new ErrorMsgException('this folder have another folders inside it');

        LevelServices::checkUnitAndLessonBelongsToLevelSubject($this->level_subject_id,$this->unit_id,$this->lesson_id);
        AssignmentServices::checkAddAssignmentAuthorization($this->level_subject_id,$user,$this->my_teacher_id);
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
//            'level_subject_id' => 'required|exists:level_subjects,id',
            'level_subject_id' => 'required|exists:'.(new LevelSubject())->getTable().',id',
            //we put required_with to ensure that the unit_id will always sent
            //when lesson_id is present
//            'unit_id' => 'required_with:lesson_id|nullable|exists:units,id',
            'unit_id' => 'required_with:lesson_id|nullable|exists:'.(new Unit())->getTable().',id',
//            'lesson_id' => 'nullable|exists:lessons,id',
            'lesson_id' => 'nullable|exists:'.(new Lesson())->getTable().',id',

            'assignment_folder_id' => 'required',

            'name' => 'required|string',
            'description' => 'required|string',

            'is_locked' => 'boolean',
            'is_hidden' => 'boolean',
            'prevent_request_help' => 'boolean',
            'display_mark' => 'boolean',
            'is_auto_saved' => 'boolean',
            'prevent_moved_between_pages' => 'boolean',
            'is_shuffling' => 'boolean',

            'timer' => 'nullable|date_format:Y/m/d H:i:s',

            'pages' => 'nullable|array',

//            'my_teacher_id' => 'nullable|exists:teachers,id',
            'my_teacher_id' => 'nullable|exists:'.(new Teacher())->getTable().',id',

        ];
    }
}
