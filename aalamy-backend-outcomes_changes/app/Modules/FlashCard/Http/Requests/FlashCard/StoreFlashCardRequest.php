<?php

namespace Modules\FlashCard\Http\Requests\FlashCard;

use App\Http\Traits\AuthorizesAfterValidation;
use App\Http\Traits\ResponseValidationFormRequest;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Modules\Assignment\Models\Assignment;
use Modules\FlashCard\Http\Controllers\Classes\FlashCardServices;
use Modules\FlashCard\Traits\ValidationAttributesTrans;
use Modules\User\Http\Controllers\Classes\UserServices;
use Modules\User\Models\Teacher;

class StoreFlashCardRequest extends FormRequest
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
        $assignment = Assignment::findOrFail($this->assignment_id);
        FlashCardServices::checkAddFlashCard($assignment,$user,$this->my_teacher_id);
//        AssignmentServices::checkUseAssignmentAuthorization($assignment,$user,$this->my_teacher_id);
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
//            'assignment_id' => 'required|exists:assignments,id',
            'assignment_id' => 'required|exists:'.(new Assignment())->getTable().',id',
            'display_time_in_seconds' => 'nullable|integer',
            'success_percentage' => 'required|integer|between:0,100',
            'quiz_time' => 'required|integer',
//            'quiz_time_type' => 'required',
            'cards' => 'nullable|array',
            'cards.*.question' => 'string',
            'cards.*.answer' => 'required_with:cards.*.question|string',

            'generate_quiz' => 'nullable|boolean',
            'quiz_question_num' => 'nullable|numeric',
            'quiz_types' => 'nullable|array|required_with:generate_quiz',
            'quiz_types.*' => ['nullable',
                Rule::in(config('FlashCard.panel.question_quiz_types'))],


//            'my_teacher_id' => 'nullable|exists:teachers,id',
            'my_teacher_id' => 'nullable|exists:'.(new Teacher())->getTable().',id',
        ];
    }
}
