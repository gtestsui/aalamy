<?php

namespace Modules\RosterAssignment\Http\Requests\RosterAssignment;

use App\Exceptions\ErrorUnAuthorizationException;
use App\Http\Traits\AuthorizesAfterValidation;
use App\Http\Traits\ResponseValidationFormRequest;
use Illuminate\Foundation\Http\FormRequest;
use Modules\Level\Http\Controllers\Classes\LevelServices;
use Modules\Level\Http\Controllers\Classes\ManageLevel\LevelManagementFactory;
use Modules\RosterAssignment\Traits\ValidationAttributesTrans;
use Modules\Roster\Http\Controllers\Classes\ManageRoster\RosterManagementFactory;
use Modules\Roster\Models\Roster;
use Modules\User\Http\Controllers\Classes\UserServices;
use Modules\User\Models\Teacher;

class GetRosterAssignmentRosterByIdForGenerateGradeBookRequest extends FormRequest
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

    private Roster $roster;
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
        UserServices::checkRoles($user,['educator','school','student']);
        $assignmentClass = RosterManagementFactory::create($user);
        $roster = $assignmentClass->myRosterByIdOrFail($this->roster_id);

        $levelManagment = LevelManagementFactory::create($user);
        $levelSubject = $levelManagment->myLevelSubjectById($this->level_subject_id);
        if(is_null($levelSubject))
            throw new ErrorUnAuthorizationException();

        LevelServices::checkUnitAndLessonBelongsToLevelSubject($this->level_subject_id,$this->unit_id,$this->lesson_id);


//        $this->setRoster($roster);
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
            'level_subject_id' => 'required',
            'unit_id' => 'nullable|required_with:lesson_id',
            'lesson_id' => 'nullable',

//            'my_teacher_id' => 'nullable|exists:teachers,id',
            'my_teacher_id' => 'nullable|exists:'.(new Teacher())->getTable().',id',

        ];
    }

    public function setRoster(Roster $roster){
        $this->roster = $roster;
    }

    public function getRoster(){
        return $this->roster;
    }
}
