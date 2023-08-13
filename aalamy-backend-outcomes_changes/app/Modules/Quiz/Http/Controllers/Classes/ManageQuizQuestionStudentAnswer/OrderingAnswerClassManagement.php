<?php

namespace Modules\Quiz\Http\Controllers\Classes\ManageQuizQuestionStudentAnswer;

use App\Exceptions\ErrorMsgException;
use App\Http\Controllers\Classes\ApplicationModules;
use Carbon\Carbon;
use Modules\QuestionBank\Http\DTO\QuestionBankData;
use Modules\QuestionBank\Models\LibraryQuestion;
use Modules\QuestionBank\Models\LibraryQuestionOrdering;
use Modules\QuestionBank\Models\QuestionBank;
use Modules\QuestionBank\Models\QuestionBankOrdering;
use Modules\Quiz\Models\QuizQuestion;
use Modules\Quiz\Models\QuizQuestionOrderingAnswer;
use Modules\Quiz\Models\QuizQuestionStudentAnswer;

class OrderingAnswerClassManagement extends BaseManageQuestionAbstract
{




    public function checkAnswer(QuizQuestion $quizQuestion,$answerObject,QuizQuestionStudentAnswer &$quizQuestionStudentAnswer){
        $orderingTexts = $quizQuestion->QuestionBank->Ordering;
        if(empty($orderingTexts))
            throw new ErrorMsgException('invalid question type with answers');

        if(!isset($answerObject['ordering'])){
            $quizQuestionStudentAnswer->update([
                'answer_status' => false,
                'mark' => 0,
            ]);
            return;
        }

        //we have make this because the student can answer question ,question so he can update answers
        $quizQuestionOrderingAnswer = QuizQuestionOrderingAnswer::where('quiz_question_student_answer_id',$quizQuestionStudentAnswer->id)
            ->first();
        if(!is_null($quizQuestionOrderingAnswer)){
            QuizQuestionOrderingAnswer::where('quiz_question_student_answer_id',$quizQuestionStudentAnswer->id)
                ->delete();
        }

        $correctAnswer = true;
        $mark = $quizQuestion->mark;
        foreach ($answerObject['ordering'] as $orderObject){
            $answerStatus = true;//for each blank
            $orderFromData = $orderingTexts->where('id',$orderObject['order_text_id'])->first();
            //if one failed that mean the all answer on this question its invalid
            if(is_null($orderFromData) || $orderFromData->order != $orderObject['order'] ){
                $correctAnswer = false;
                $mark = 0;
                $answerStatus = false;
            }

            QuizQuestionOrderingAnswer::create([
                'quiz_question_student_answer_id' => $quizQuestionStudentAnswer->id,
                'order_text_id' => $orderObject['order_text_id'],
                'order' => $orderObject['order'],
                'answer_status' => $answerStatus,
            ]);
        }

        //if the student missed order all texts
        if(count($answerObject['ordering']) < count($orderingTexts)){
            $correctAnswer = false;
            $mark = 0;
        }

        $quizQuestionStudentAnswer->update([
            'answer_status' => $correctAnswer,
            'mark' => $mark,
        ]);

    }


}
