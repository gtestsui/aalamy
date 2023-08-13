<?php

namespace Modules\Quiz\Http\Requests\QuizStudentAnswer;

use App\Exceptions\ErrorUnAuthorizationException;
use App\Http\Traits\AuthorizesAfterValidation;
use App\Http\Traits\ResponseValidationFormRequest;
use Illuminate\Foundation\Http\FormRequest;
use Modules\Quiz\Http\Controllers\Classes\ManageQuiz\StudentQuiz;
use Modules\Quiz\Models\Quiz;
use Modules\RosterAssignment\Traits\ValidationAttributesTrans;
use Modules\User\Http\Controllers\Classes\UserServices;

class StartQuizRequest extends FormRequest
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


    private Quiz $quiz ;

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
        list(,$student)=UserServices::getAccountTypeAndObject($user);


        $studentQuizClass = new StudentQuiz($student);
        $quiz = $studentQuizClass->getQuizByIdICanAnswerItNow($this->route('quiz_id'));
        if(is_null($quiz))
            throw new ErrorUnAuthorizationException();

        $this->setQuiz($quiz);

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



    public function setQuiz(Quiz $quiz){
        $this->quiz = $quiz;
    }

    public function getQuiz(){
        return $this->quiz;
    }




}
