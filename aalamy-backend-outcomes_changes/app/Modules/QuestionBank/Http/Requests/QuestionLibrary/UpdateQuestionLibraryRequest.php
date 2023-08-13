<?php

namespace Modules\QuestionBank\Http\Requests\QuestionLibrary;


use App\Http\Controllers\Classes\ApplicationModules;
use App\Http\Traits\AuthorizesAfterValidation;
use App\Http\Traits\ResponseValidationFormRequest;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Modules\ClassModule\Traits\ValidationAttributesTrans;
use Modules\QuestionBank\Http\Controllers\Classes\ManageQuestionLibraryByAccountType\MyOwnQuestionLibrary\MyOwnQuestionLibraryByAccountTypeManagementFactory;
use Modules\QuestionBank\Http\Controllers\Classes\ManageQuestionLibraryByAccountType\QuestionLibraryByAccountTypeManagementFactory;
use Modules\QuestionBank\Models\LibraryQuestion;
use Modules\User\Http\Controllers\Classes\UserServices;


class UpdateQuestionLibraryRequest extends FormRequest
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


    private LibraryQuestion $questionLibrary;

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
//        $manageQuestionLibrary = QuestionLibraryByAccountTypeManagementFactory::create($user);
        $manageQuestionLibrary = MyOwnQuestionLibraryByAccountTypeManagementFactory::create($user);
        $questionLibrary = $manageQuestionLibrary->getMyQuestionLibraryByIdOrFail($this->route('id'));

        $this->setQuestionLibrary($questionLibrary);
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
            'share_type' => ['required',Rule::in((configFromModule('panel.question_share_types_with_library',ApplicationModules::QUESTION_BANK_MODULE_NAME)))]
        ];
    }

    public function setQuestionLibrary(LibraryQuestion $questionLibrary){
        $this->questionLibrary = $questionLibrary;
    }

    public function getQuestionLibrary(){
        return $this->questionLibrary;
    }
}
