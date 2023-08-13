<?php

namespace Modules\Mark\Http\Requests\GradeBook;

use App\Exceptions\ErrorUnAuthorizationException;
use App\Http\Traits\AuthorizesAfterValidation;
use App\Http\Traits\ResponseValidationFormRequest;
use Illuminate\Foundation\Http\FormRequest;
use Modules\Level\Http\Controllers\Classes\LevelServices;
use Modules\Level\Http\Controllers\Classes\ManageLevel\LevelManagementFactory;
use Modules\Roster\Http\Controllers\Classes\ManageRoster\RosterManagementFactory;
use Modules\RosterAssignment\Traits\ValidationAttributesTrans;
use Modules\User\Http\Controllers\Classes\UserServices;
use Modules\User\Models\Student;
use Modules\User\Models\Teacher;

class GenerateGradeBookRequest extends FormRequest
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

//    private Roster $roster;
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


        $rosterClass =RosterManagementFactory::create($user);
        $roster = $rosterClass->myRosterById($this->route('roster_id'));
        if(is_null($roster))
            throw new ErrorUnAuthorizationException();

        $levelManagment = LevelManagementFactory::create($user);
        $levelSubject = $levelManagment->myLevelSubjectById($this->level_subject_id);
        if(is_null($levelSubject))
            throw new ErrorUnAuthorizationException();

        LevelServices::checkUnitAndLessonBelongsToLevelSubject($this->level_subject_id,$this->unit_id,$this->lesson_id);




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

            'grade_book_name' => 'required',
            'level_subject_id' => 'required',
//            'unit_id' => 'nullable',
//            'lesson_id' => 'nullable',

//            'assignment_weight' => 'required_with:roster_assignment_ids|integer',
//            'quiz_weight' => 'required_with:quizzes_ids|integer',

            'roster_assignments' => 'required_without:quizzes|required_with:assignment_weight|array',
            'roster_assignments.*.id' => 'required_with:roster_assignments|integer',
            'roster_assignments.*.weight' => 'required_with:roster_assignments',

            'quizzes' => 'required_without:roster_assignments|required_with:quiz_weight|array',
            'quizzes.*.id' => 'required_with:quizzes',
            'quizzes.*.weight' => 'required_with:quizzes|integer',

            'external_marks_weight' => 'required_with:external_marks|integer',
            'external_marks' => 'required_with:external_marks_weight|array',
            'external_marks.*.student_id' => 'required_with:external_marks|exists:'.(new Student())->getTable().',id',
            'external_marks.*.mark' => 'required_with:external_marks|integer',




//            'my_teacher_id' => 'nullable|exists:teachers,id',
            'my_teacher_id' => 'nullable|exists:'.(new Teacher())->getTable().',id',

        ];
    }

//    public function setRoster(Roster $roster){
//        $this->roster = $roster;
//    }
//
//    public function getRoster(){
//        return $this->roster;
//    }

}
