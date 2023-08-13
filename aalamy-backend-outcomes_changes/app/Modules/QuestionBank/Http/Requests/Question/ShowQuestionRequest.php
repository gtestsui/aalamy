<?php

namespace Modules\QuestionBank\Http\Requests\Question;


use App\Http\Traits\AuthorizesAfterValidation;
use App\Http\Traits\ResponseValidationFormRequest;
use Illuminate\Foundation\Http\FormRequest;
use Modules\ClassModule\Traits\ValidationAttributesTrans;
use Modules\QuestionBank\Http\Controllers\Classes\ManageQuestionByAccountType\QuestionByAccountTypeManagementFactory;
use Modules\QuestionBank\Http\Controllers\Classes\ManageQuestionLibraryByAccountType\QuestionLibraryByAccountTypeManagementFactory;
use Modules\QuestionBank\Models\LibraryQuestion;
use Modules\QuestionBank\Models\QuestionBank;
use Modules\User\Http\Controllers\Classes\UserServices;


class ShowQuestionRequest extends FormRequest
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
        UserServices::checkRoles($user,['educator','school','student','parent']);
        $manageQuestionBank = QuestionByAccountTypeManagementFactory::create($user);
        $questionBank = $manageQuestionBank->getMyQuestionBankByIdOrFail($this->route('id'));

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

        ];
    }

    public function setQuestionBank(QuestionBank $questionBank){
        $this->questionBank = $questionBank;
    }

    public function getQuestionBank(){
        return $this->questionBank;
    }
}
