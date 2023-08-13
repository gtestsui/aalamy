<?php

namespace Modules\Assignment\Http\Requests\Assignment;

use App\Http\Controllers\Classes\RequestServicesClass;
use App\Http\Traits\AuthorizesAfterValidation;
use App\Http\Traits\ResponseValidationFormRequest;
use Illuminate\Foundation\Http\FormRequest;
use Modules\Assignment\Http\Controllers\Classes\AssignmentServices;
use Modules\Assignment\Models\Assignment;
use Modules\Assignment\Traits\ValidationAttributesTrans;
use Modules\Level\Http\Controllers\Classes\LevelServices;
use Modules\Level\Models\Lesson;
use Modules\Level\Models\LevelSubject;
use Modules\Level\Models\Unit;
use Modules\User\Http\Controllers\Classes\UserServices;
use Modules\User\Models\Teacher;

class UpdateAssignmentRequest extends FormRequest
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

    private Assignment $assignment;
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
        $assignment = Assignment::findOrFail($this->route('id'));
        AssignmentServices::checkUpdateAssignmentAuthorization($assignment,$user,$this->my_teacher_id);
        $this->setAssignment($assignment);
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
            'level_subject_id' => 'nullable|exists:'.(new LevelSubject())->getTable().',id',
            //we put required_with to ensure that the unit_id will always sent
            //when lesson_id is present
//            'unit_id' => 'required_with:lesson_id|nullable|exists:units,id',
            'unit_id' => 'required_with:lesson_id|nullable|exists:'.(new Unit())->getTable().',id',
//            'lesson_id' => 'nullable|exists:lessons,id',
            'lesson_id' => 'nullable|exists:'.(new Lesson())->getTable().',id',

            'name' => 'nullable|string',
            'description' => 'nullable|string',

            'is_locked' => 'nullable|boolean',
            'is_hidden' => 'nullable|boolean',
            'prevent_request_help' => 'nullable|boolean',
            'display_mark' => 'nullable|boolean',
            'is_auto_saved' => 'nullable|boolean',
            'prevent_moved_between_pages' => 'nullable|boolean',
            'is_shuffling' => 'nullable|boolean',
            'timer' => 'nullable|date_format:Y/m/d H:i:s',

//            'my_teacher_id' => 'nullable|exists:teachers,id',
            'my_teacher_id' => 'nullable|exists:'.(new Teacher())->getTable().',id',

        ];
    }


    public function setAssignment(Assignment $assignment){
        $this->assignment = $assignment;
    }

    public function getAssignment(){
        return $this->assignment;
    }


}
