<?php

namespace Modules\Quiz\Http\Requests\Quiz;

use App\Exceptions\ErrorMsgException;
use App\Http\Traits\AuthorizesAfterValidation;
use App\Http\Traits\ResponseValidationFormRequest;
use Illuminate\Foundation\Http\FormRequest;
use Modules\Assignment\Http\Controllers\Classes\AssignmentServices;
use Modules\Assignment\Models\Assignment;
use Modules\Level\Http\Controllers\Classes\LevelServices;
use Modules\Level\Models\Lesson;
use Modules\Level\Models\LevelSubject;
use Modules\Level\Models\Unit;
use Modules\Quiz\Http\Controllers\Classes\QuizSettingClass;
use Modules\RosterAssignment\Traits\ValidationAttributesTrans;
use Modules\Roster\Models\Roster;
use Modules\User\Http\Controllers\Classes\UserServices;
use Modules\User\Models\Teacher;

class GenerateRandomQuizRequest extends FormRequest
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


        $quizClass = new QuizSettingClass($this);
        $quizClass->checkQuestionsDetails();

        if($this->hard_questions_count == 0 && $this->hard_questions_mark_percentage !=0)
            throw new ErrorMsgException('invalid count with percentage');
        if($this->medium_questions_count == 0 && $this->medium_questions_mark_percentage !=0)
            throw new ErrorMsgException('invalid count with percentage');
        if($this->easy_questions_count == 0 && $this->easy_questions_mark_percentage !=0)
            throw new ErrorMsgException('invalid count with percentage');

        LevelServices::checkUnitAndLessonBelongsToLevelSubject($this->level_subject_id,$this->unit_id,$this->lesson_id);
        LevelServices::checkUseLevelSubjectAuthorization($this->level_subject_id,$user,$this->my_teacher_id);

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

            'roster_id' => 'required|exists:'.(new Roster())->getTable().',id',

            'level_subject_id' => 'required|exists:'.(new LevelSubject())->getTable().',id',

//            'unit_id' => 'nullable|required_with:lesson_id|exists:'.(new Unit())->getTable().',id',
//            'lesson_id' => 'nullable|exists:'.(new Lesson())->getTable().',id',

            'unit_ids' => 'nullable|array',
            'unit_ids.*' => 'nullable|exists:'.(new Unit())->getTable().',id',
            'lesson_ids' => 'nullable|array',
            'lesson_ids.*' => 'nullable|exists:'.(new Lesson())->getTable().',id',

            'name' => 'required',
            'prevent_display_answers' => 'nullable|boolean',
//            'mark' => 'required|integer|min:0',
            'time' => 'required|integer',
            'start_date' => 'required|date_format:'.config('panel.standard_date_time_format').'|after:'.date('Y-m-d H:i:'),
            'end_date' => 'required|date_format:'.config('panel.standard_date_time_format').'|after:start_date',

            'questions_count' => 'required|integer|min:0',
            'hard_questions_count' => 'required|integer|min:0',
            'medium_questions_count' => 'required|integer|min:0',
            'easy_questions_count' => 'required|integer|min:0',

            'hard_questions_mark_percentage' => 'required|integer|min:0|max:100',
            'medium_questions_mark_percentage' => 'required|integer|min:0|max:100',
            'easy_questions_mark_percentage' => 'required|integer|min:0|max:100',


//            'teacher_id' => 'nullable|exists:teachers,id',
            'teacher_id' => 'nullable|exists:'.(new Teacher())->getTable().',id',

        ];

    }




}
