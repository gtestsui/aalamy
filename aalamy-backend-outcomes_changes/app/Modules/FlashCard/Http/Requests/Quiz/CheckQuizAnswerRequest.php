<?php

namespace Modules\FlashCard\Http\Requests\Quiz;

use App\Http\Controllers\Classes\RequestServicesClass;
use App\Http\Traits\AuthorizesAfterValidation;
use App\Http\Traits\ResponseValidationFormRequest;
use Illuminate\Foundation\Http\FormRequest;
use Modules\Assignment\Models\Assignment;
use Modules\FlashCard\Http\Controllers\Classes\FlashCardServices;
use Modules\FlashCard\Models\FlashCard;
use Modules\FlashCard\Models\MultiChoiceChoice;
use Modules\FlashCard\Models\MultiChoiceQuestion;
use Modules\FlashCard\Models\TrueFalseQuestion;
use Modules\FlashCard\Traits\ValidationAttributesTrans;
use Modules\User\Http\Controllers\Classes\UserServices;

class CheckQuizAnswerRequest extends FormRequest
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
        UserServices::checkRoles($user,['student']);
//        $flashCard = FlashCard::findOrFail($this->route('flash_card_id'));
//        FlashCardServices::checkUseFlashCard($flashCard,$user,$this->my_teacher_id);
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
//            'flash_card_id' => 'required|exists:flash_cards,id',

            'multi_choice_answers' => 'nullable|array',
            'multi_choice_answers.*.question_id' => 'required_with:multi_choice_answers|exists:'.(new MultiChoiceQuestion())->getTable().',id',
//            'multi_choice_answers.*.choices' => 'required_with:multi_choice_answers.*.question_id|array',
            'multi_choice_answers.*.choice_id' => 'required_with:multi_choice_answers.*.question_id|exists:'.(new MultiChoiceChoice())->getTable().',id',

            'true_false_answers' => 'nullable|array',
            'true_false_answers.*.question_id' => 'required_with:true_false_answers|exists:'.(new TrueFalseQuestion())->getTable().',id',
            'true_false_answers.*.answer' => 'required_with:true_false_answers.*.question_id|boolean',

        ];
    }
}
