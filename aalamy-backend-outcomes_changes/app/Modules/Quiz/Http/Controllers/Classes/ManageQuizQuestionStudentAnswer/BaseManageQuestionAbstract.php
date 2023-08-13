<?php


namespace Modules\Quiz\Http\Controllers\Classes\ManageQuizQuestionStudentAnswer;


use Modules\QuestionBank\Http\DTO\QuestionBankData;
use Modules\QuestionBank\Models\LibraryQuestion;
use Modules\QuestionBank\Models\QuestionBank;
use Modules\Quiz\Models\QuizQuestion;
use Modules\Quiz\Models\QuizQuestionStudentAnswer;

abstract class BaseManageQuestionAbstract
{


    /**
     * @param QuizQuestion $quizQuestion
     * @param $answerObject , comes from request by student
     * @param QuizQuestionStudentAnswer $quizQuestionStudentAnswer
     * @return mixed
     */
    abstract public function checkAnswer(QuizQuestion $quizQuestion,$answerObject,QuizQuestionStudentAnswer &$quizQuestionStudentAnswer);


}
