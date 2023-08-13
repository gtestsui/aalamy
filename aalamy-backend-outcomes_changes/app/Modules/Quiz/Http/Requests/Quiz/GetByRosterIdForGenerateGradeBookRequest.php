<?php

namespace Modules\Quiz\Http\Requests\Quiz;

use App\Exceptions\ErrorMsgException;
use App\Exceptions\ErrorUnAuthorizationException;
use App\Http\Traits\AuthorizesAfterValidation;
use App\Http\Traits\ResponseValidationFormRequest;
use Illuminate\Foundation\Http\FormRequest;
use Modules\Assignment\Http\Controllers\Classes\AssignmentServices;
use Modules\Assignment\Models\Assignment;
use Modules\Level\Http\Controllers\Classes\LevelServices;
use Modules\Level\Http\Controllers\Classes\ManageLevel\LevelManagementFactory;
use Modules\Quiz\Http\Controllers\Classes\ManageQuiz\QuizManagementFactory;
use Modules\Quiz\Models\Quiz;
use Modules\Roster\Http\Controllers\Classes\ManageRoster\RosterManagementFactory;
use Modules\RosterAssignment\Models\RosterAssignment;
use Modules\RosterAssignment\Traits\SharedValidationForStoreRosterAssignment;
use Modules\RosterAssignment\Traits\ValidationAttributesTrans;
use Modules\Roster\Http\Controllers\Classes\RosterServices;
use Modules\Roster\Models\Roster;
use Modules\User\Http\Controllers\Classes\UserServices;

class GetByRosterIdForGenerateGradeBookRequest extends FormRequest
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

        $rosterManagmentClass = RosterManagementFactory::create($user);
        $roster = $rosterManagmentClass->myRosterById($this->route('roster_id'));
        if(is_null($roster))
            throw new ErrorUnAuthorizationException();

        $levelManagment = LevelManagementFactory::create($user);
        $levelSubject = $levelManagment->myLevelSubjectById($this->level_subject_id);
        if(is_null($levelSubject))
            throw new ErrorUnAuthorizationException();

//        LevelServices::checkUnitAndLessonBelongsToLevelSubject($this->level_subject_id,$this->unit_id,$this->lesson_id);


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
//            'unit_id' => 'nullable|required_with:lesson_id',
//            'lesson_id' => 'nullable',
        ];

    }





}
