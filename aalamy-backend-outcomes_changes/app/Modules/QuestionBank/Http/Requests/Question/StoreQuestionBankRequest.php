<?php

namespace Modules\QuestionBank\Http\Requests\Question;

use App\Exceptions\ErrorMsgException;
use App\Http\Traits\AuthorizesAfterValidation;
use App\Http\Traits\ResponseValidationFormRequest;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Modules\ClassModule\Traits\ValidationAttributesTrans;
use Modules\Level\Http\Controllers\Classes\LevelServices;
use Modules\Level\Models\Lesson;
use Modules\Level\Models\LevelSubject;
use Modules\Level\Models\Unit;
use Modules\QuestionBank\Http\Controllers\Classes\ManageQuestion\QuestionManagementFactory;
use Modules\User\Http\Controllers\Classes\UserServices;
use Modules\User\Models\Teacher;

class StoreQuestionBankRequest extends FormRequest
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

        LevelServices::checkUnitAndLessonBelongsToLevelSubject($this->level_subject_id,$this->unit_id,$this->lesson_id);
        LevelServices::checkUseLevelSubjectAuthorization($this->level_subject_id,$user,$this->my_teacher_id);

        return true;
    }

    public function authorize()
    {
        //we should check on the type because we will use it before the validation done
        if(!in_array($this->question_type,config('QuestionBank.panel.question_types')))
            throw new ErrorMsgException('invalid question type filed');

        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {

        $questionValidation = [
            'question' => 'required',
            'question_type' => ['required',Rule::in(config('QuestionBank.panel.question_types'))],
            'difficult_level' => ['required',Rule::in(config('QuestionBank.panel.question_difficult_level'))],
            'level_subject_id' => 'required|exists:'.(new LevelSubject())->getTable().',id',
            'unit_id' => 'nullable|required_with:lesson_id|exists:'.(new Unit())->getTable().',id',
            'lesson_id' => 'nullable|exists:'.(new Lesson())->getTable().',id',

//            'teacher_id' => 'nullable|exists:teachers,id',
            'teacher_id' => 'nullable|exists:'.(new Teacher())->getTable().',id',
        ];

        $validationClassByType = QuestionManagementFactory::createStoreValidationClass($this->question_type);
        $validationRulesByQuestionType = $validationClassByType->rules();

        return array_merge($questionValidation,$validationRulesByQuestionType);
    }
}
