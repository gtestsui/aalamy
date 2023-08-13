<?php

namespace Modules\QuestionBank\Http\Requests\Question;


use App\Http\Controllers\Classes\ApplicationModules;
use App\Http\Traits\AuthorizesAfterValidation;
use App\Http\Traits\ResponseValidationFormRequest;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Modules\ClassModule\Traits\ValidationAttributesTrans;
use Modules\QuestionBank\Http\Controllers\Classes\ManageQuestionByAccountType\QuestionByAccountTypeManagementFactory;
use Modules\QuestionBank\Http\Controllers\Classes\QuestionServices;
use Modules\QuestionBank\Models\QuestionBank;
use Modules\User\Http\Controllers\Classes\UserServices;
use Modules\User\Models\Teacher;

class ShareQuestionBankRequest extends FormRequest
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

        $questionManagemnet = QuestionByAccountTypeManagementFactory::create($user,$this->my_teacher_id);
        $questionBank = $questionManagemnet->getMyQuestionBankByIdOrFail($this->route('id'));
        //check if shared before
        QuestionServices::checkCanShareWithLibrary($questionBank);
        QuestionServices::checkValidShareTypeWithMyAccount($questionBank,$this->share_type);

        $this->setQuestionBank($questionBank);
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

            'share_type' => ['required',Rule::in(configFromModule(
                'panel.question_share_types_with_library',ApplicationModules::QUESTION_BANK_MODULE_NAME
            ))],

//            'teacher_id' => 'nullable|exists:teachers,id',
            'teacher_id' => 'nullable|exists:'.(new Teacher())->getTable().',id',
        ];

    }


    public function setQuestionBank(QuestionBank $questionBank){
        $this->questionBank = $questionBank;
    }

    public function getQuestionBank(){
        return $this->questionBank;
    }

}
