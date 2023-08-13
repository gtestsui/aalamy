<?php

namespace Modules\Quiz\Http\Requests\QuizStudentAnswer;

use App\Exceptions\ErrorMsgException;
use App\Exceptions\ErrorUnAuthorizationException;
use App\Http\Controllers\Classes\ApplicationModules;
use App\Http\Traits\AuthorizesAfterValidation;
use App\Http\Traits\ResponseValidationFormRequest;
use Illuminate\Foundation\Http\FormRequest;
use Modules\Assignment\Http\Controllers\Classes\AssignmentServices;
use Modules\Assignment\Models\Assignment;
use Modules\Quiz\Http\Controllers\Classes\ManageQuiz\QuizManagementFactory;
use Modules\Quiz\Http\Controllers\Classes\ManageQuiz\StudentQuiz;
use Modules\Quiz\Models\Quiz;
use Modules\Quiz\Models\QuizStudent;
use Modules\RosterAssignment\Models\RosterAssignment;
use Modules\RosterAssignment\Traits\SharedValidationForStoreRosterAssignment;
use Modules\RosterAssignment\Traits\ValidationAttributesTrans;
use Modules\Roster\Http\Controllers\Classes\RosterServices;
use Modules\Roster\Models\Roster;
use Modules\User\Http\Controllers\Classes\UserServices;

class GetStudentsMarksByQuizIdRequest extends FormRequest
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
        UserServices::checkRoles($user,[/*'student',*/'school','educator']);

        $quizClass = QuizManagementFactory::create($user);
        $quiz = $quizClass->getMyQuizById($this->route('quiz_id'));
        if(is_null($quiz))
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

        ];

    }

    public function setQuiz(Quiz $quiz){
        $this->quiz = $quiz;
    }

    public function getQuiz(){
        return $this->quiz;
    }




}
