<?php

namespace Modules\DiscussionCorner\Http\Requests\SurveyAnswer;

use App\Exceptions\ErrorMsgException;
use App\Http\Controllers\Classes\ApplicationModules;
use App\Http\Controllers\Classes\RequestServicesClass;
use App\Http\Traits\AuthorizesAfterValidation;
use App\Http\Traits\ResponseValidationFormRequest;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Modules\DiscussionCorner\Http\Controllers\Classes\DiscussionCornerServices;
use Modules\DiscussionCorner\Models\DiscussionCornerPost;
use Modules\DiscussionCorner\Models\DiscussionCornerSurvey;
use Modules\DiscussionCorner\Models\DiscussionCornerSurveyQuestion;
use Modules\DiscussionCorner\Models\DiscussionCornerSurveyQuestionChoice;
use Modules\DiscussionCorner\Models\DiscussionCornerSurveyUser;
use Modules\HelpCenter\Traits\ValidationAttributesTrans;
use Modules\User\Http\Controllers\Classes\UserServices;
use Modules\User\Models\Teacher;

class StoreSurveyAnswerRequest extends FormRequest
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
        $foundAnswers = DiscussionCornerSurveyUser::where('survey_id',$this->route('survey_id'))
            ->where('user_id',$user->id)->first();
        if(!is_null($foundAnswers))
            throw new ErrorMsgException(transMsg('survey_answered_before',ApplicationModules::DISCUSSION_CORNER_MODULE_NAME));
        $survey = DiscussionCornerSurvey::findOrFail($this->route('survey_id'));
        $discussionClass  = DiscussionCornerServices::initializeManageDiscussionClass($survey->educator_id,$survey->school_id);
        list($userAccountType,$userAccountObject) = UserServices::getAccountTypeAndObject($user,$this->my_teacher_id);

        $discussionClass->{'checkReplyOnPostBy'.ucfirst($userAccountType)}($userAccountObject);


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
//            'survey_id' => 'required|exists:discussion_corner_surveys,id',
            'answers' => 'required|array',
//            'answers.*.question_id' => 'required|exists:discussion_corner_survey_questions,id',
            'answers.*.question_id' => 'required|exists:'.(new DiscussionCornerSurveyQuestion())->getTable().',id',
//            'answers.*.choice_id' => 'required_without:answers.*.written_answer|exists:discussion_corner_survey_question_choices,id',
            'answers.*.choice_id' => 'required_without:answers.*.written_answer|exists:'.(new DiscussionCornerSurveyQuestionChoice())->getTable().',id',
            'answers.*.written_answer' => 'required_without:answers.*.choice_id|nullable|string',
//            'my_teacher_id' => 'nullable|exists:teachers,id',
            'my_teacher_id' => 'nullable|exists:'.(new Teacher())->getTable().',id',

        ];
    }
}
