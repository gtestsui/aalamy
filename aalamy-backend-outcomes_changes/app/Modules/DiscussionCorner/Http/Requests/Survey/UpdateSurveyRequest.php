<?php

namespace Modules\DiscussionCorner\Http\Requests\Survey;

use App\Exceptions\ErrorMsgException;
use App\Http\Traits\AuthorizesAfterValidation;
use App\Http\Traits\ResponseValidationFormRequest;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Modules\DiscussionCorner\Http\Controllers\Classes\DiscussionCornerServices;
use Modules\DiscussionCorner\Models\DiscussionCornerSurvey;
use Modules\DiscussionCorner\Models\DiscussionCornerSurveyQuestion;
use Modules\DiscussionCorner\Models\DiscussionCornerSurveyQuestionChoice;
use Modules\DiscussionCorner\Traits\ValidationAttributesTrans;
use Modules\User\Http\Controllers\Classes\UserServices;
use Modules\User\Models\Teacher;

class UpdateSurveyRequest extends FormRequest
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


    protected DiscussionCornerSurvey $survey;

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

        $survey = DiscussionCornerSurvey::findOrFail($this->route('id'));

        $discussionClass  = DiscussionCornerServices::initializeManageDiscussionClass($survey->educator_id,$survey->school_id);

        $discussionClass->checkUpdateSurvey($user,$survey);
        $this->setSurvey($survey);


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
            'subject' => 'required|string',
            'description' => 'required|string',
            'priority' => ['required',Rule::in(config('DiscussionCorner.panel.survey_priority_values'))],

            'questions' => 'array|required',
//            'questions.*.id' => 'nullable|exists:discussion_corner_survey_questions,id',
            'questions.*.id' => 'nullable|exists:'.(new DiscussionCornerSurveyQuestion())->getTable().',id',
            'questions.*.question' => 'required|string',
            'questions.*.is_required' => 'required|boolean',
            'questions.*.question_type' => ['required',Rule::in(config('DiscussionCorner.panel.survey_question_types'))],
            'questions.*.choices' => 'required_if:questions.*.question_type,choice|nullable|array',
//            'questions.*.choices.*.id' => 'nullable|exists:discussion_corner_survey_question_choices,id',
            'questions.*.choices.*.id' => 'nullable|exists:'.(new DiscussionCornerSurveyQuestionChoice())->getTable().',id',
            'questions.*.choices.*.choice' => 'required_with:questions.*.choices|nullable|string',


//            'my_teacher_id' => 'nullable|exists:teachers,id',
            'my_teacher_id' => 'nullable|exists:'.(new Teacher())->getTable().',id',

        ];
    }


    public function setSurvey(DiscussionCornerSurvey $survey){
        $this->survey = $survey;
    }

    public function getSurvey(){
        return $this->survey;
    }
}
