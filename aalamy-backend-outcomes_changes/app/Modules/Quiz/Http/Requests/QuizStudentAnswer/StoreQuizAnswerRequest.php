<?php

namespace Modules\Quiz\Http\Requests\QuizStudentAnswer;

use App\Exceptions\ErrorMsgException;
use App\Exceptions\ErrorUnAuthorizationException;
use App\Http\Traits\AuthorizesAfterValidation;
use App\Http\Traits\ResponseValidationFormRequest;
use Carbon\Carbon;
use Illuminate\Foundation\Http\FormRequest;
use Modules\QuestionBank\Models\QuestionBankJumbleSentence;
use Modules\QuestionBank\Models\QuestionBankMatchingLeftList;
use Modules\QuestionBank\Models\QuestionBankMatchingRightList;
use Modules\QuestionBank\Models\QuestionBankMultiChoice;
use Modules\QuestionBank\Models\QuestionBankOrdering;
use Modules\Quiz\Http\Controllers\Classes\ManageQuiz\StudentQuiz;
use Modules\Quiz\Models\Quiz;
use Modules\Quiz\Models\QuizQuestion;
use Modules\Quiz\Models\QuizStudent;
use Modules\RosterAssignment\Traits\ValidationAttributesTrans;
use Modules\User\Http\Controllers\Classes\UserServices;
use Modules\User\Models\Teacher;

class StoreQuizAnswerRequest extends FormRequest
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

//        $quizClass = QuizManagementFactory::createForDisplay($user);
//        $quiz = $quizClass->getMyQuizById($this->route('quiz_id'));

        $studentQuizClass = new StudentQuiz($student);
        $quiz = $studentQuizClass->getQuizByIdICanAnswerItNow($this->route('quiz_id'));

        if(is_null($quiz))
            throw new ErrorUnAuthorizationException();

        $quizStudent = QuizStudent::where('student_id',$student->id)
            ->where('quiz_id',$quiz->id)
            ->firstOrFail();
        if($quizStudent->start_date < Carbon::now()->subMinutes($quiz->time)){
            throw new ErrorMsgException('quiz time has been done');
        }

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

            'answers' => 'required|array',
            'answers.*.quiz_question_id' => 'required|exists:'.(new QuizQuestion())->getTable().',id',

            'answers.*.choice_id' => 'nullable|exists:'.(new QuestionBankMultiChoice())->getTable().',id',

            'answers.*.true_false_status' => 'nullable|boolean',

            'answers.*.fill_in_blanks' => 'nullable|array',
            'answers.*.fill_in_blanks.*.word' => 'nullable|string',
            'answers.*.fill_in_blanks.*.order' => 'integer',

            'answers.*.jumble_sentence' => 'nullable|array',
            'answers.*.jumble_sentence.*.jumble_sentence_id' => 'nullable|exists:'.(new QuestionBankJumbleSentence())->getTable().',id',
            'answers.*.jumble_sentence.*.order' => 'integer',

            'answers.*.fill_text' => 'nullable|string',

            'answers.*.matching' => 'nullable|array',
            'answers.*.matching.*.left_list_id' => 'nullable|exists:'.(new QuestionBankMatchingLeftList())->getTable().',id',
            'answers.*.matching.*.right_list_id' => 'nullable|exists:'.(new QuestionBankMatchingRightList())->getTable().',id',

            'answers.*.ordering' => 'nullable|array',
            'answers.*.ordering.*.order_text_id' => 'nullable|exists:'.(new QuestionBankOrdering())->getTable().',id',
            'answers.*.ordering.*.order' => 'integer',


//            'teacher_id' => 'nullable|exists:teachers,id',
            'teacher_id' => 'nullable|exists:'.(new Teacher())->getTable().',id',

        ];

    }



    public function setQuiz(Quiz $quiz){
        $this->quiz = $quiz;
    }

    public function getQuiz(){
        return $this->quiz;
    }




}
