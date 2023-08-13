<?php

namespace Modules\DiscussionCorner\Http\Requests\SurveyAnswer;

use App\Exceptions\ErrorMsgException;
use App\Exceptions\ErrorUnAuthorizationException;
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

class GetSurveyUserAnswersByChoiceIdRequest extends FormRequest
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
        $choice = DiscussionCornerSurveyQuestionChoice::findOrFail($this->route('choice_id'));

        $question = DiscussionCornerSurveyQuestion::findOrFail($choice->question_id);
        $survey = DiscussionCornerSurvey::findOrFail($question->survey_id);

       // $survey = DiscussionCornerSurvey::findOrFail($question->survey_id);

        if($survey->user_id != $user->id)
            throw new ErrorUnAuthorizationException();


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
}
