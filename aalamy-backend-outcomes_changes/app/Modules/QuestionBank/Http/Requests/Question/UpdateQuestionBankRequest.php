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
use Modules\QuestionBank\Http\Controllers\Classes\ManageQuestionByAccountType\QuestionByAccountTypeManagementFactory;
use Modules\QuestionBank\Models\QuestionBank;
use Modules\User\Http\Controllers\Classes\UserServices;
use Modules\User\Models\Teacher;

class UpdateQuestionBankRequest extends FormRequest
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


    private QuestionBank $questionBank;


    public function authorize()
    {
        //we have used to override on this function to authorize before validation
        //because we need to use $questionBank in validation
        $user = $this->user();
        UserServices::checkRoles($user,['educator','school']);

        $questionManagemnet = QuestionByAccountTypeManagementFactory::create($user,$this->my_teacher_id);
        $questionBank = $questionManagemnet->getMyQuestionBankByIdOrFail($this->route('id'));
        $this->setQuestionBank($questionBank);
        return true;
    }

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
        $questionValidation = [
            'question' => 'required',
//            'question_type' => ['required',Rule::in(config('QuestionBank.panel.question_types'))],
            'difficult_level' => ['required',Rule::in(config('QuestionBank.panel.question_difficult_level'))],
            'level_subject_id' => 'required|exists:'.(new LevelSubject())->getTable().',id',
            'unit_id' => 'nullable|required_with:lesson_id|exists:'.(new Unit())->getTable().',id',
            'lesson_id' => 'nullable|exists:'.(new Lesson())->getTable().',id',

//            'teacher_id' => 'nullable|exists:teachers,id',
            'teacher_id' => 'nullable|exists:'.(new Teacher())->getTable().',id',
        ];
        $questionBank = $this->getQuestionBank();
        $validationClassByType = QuestionManagementFactory::createStoreValidationClass($questionBank->question_type);
        $validationRulesByQuestionType = $validationClassByType->rules();

        return array_merge($questionValidation,$validationRulesByQuestionType);
    }


    public function setQuestionBank(QuestionBank $questionBank){
        $this->questionBank = $questionBank;
    }

    public function getQuestionBank(){
        return $this->questionBank;
    }

}
