<?php

namespace Modules\Quiz\Http\Controllers\Classes\ManageQuizQuestionStudentAnswer;

use App\Exceptions\ErrorMsgException;
use Carbon\Carbon;
use Modules\QuestionBank\Http\DTO\QuestionBankData;
use Modules\QuestionBank\Models\LibraryQuestion;
use Modules\QuestionBank\Models\LibraryQuestionMatchingLeftList;
use Modules\QuestionBank\Models\LibraryQuestionMatchingRightList;
use Modules\QuestionBank\Models\QuestionBank;
use Modules\QuestionBank\Models\QuestionBankFillText;
use Modules\QuestionBank\Models\QuestionBankMatchingLeftList;
use Modules\QuestionBank\Models\QuestionBankMatchingRightList;
use Modules\Quiz\Models\QuizQuestion;
use Modules\Quiz\Models\QuizQuestionMatchingAnswer;
use Modules\Quiz\Models\QuizQuestionStudentAnswer;

class MatchingAnswerClassManagement extends BaseManageQuestionAbstract
{


    public function checkAnswer(QuizQuestion $quizQuestion,$answerObject,QuizQuestionStudentAnswer &$quizQuestionStudentAnswer){
//        MatchingLeftList.RightListRecords
        $leftLists = $quizQuestion->QuestionBank->MatchingLeftList;
        if(empty($leftLists))
            throw new ErrorMsgException('invalid question type with answers');

        if(!isset($answerObject['matching'])){
            $quizQuestionStudentAnswer->update([
                'answer_status' => false,
                'mark' => 0,
            ]);
            return;
        }

        //we have make this because the student can answer question ,question so he can update answers
        $quizQuestionMatchingAnswer = QuizQuestionMatchingAnswer::where('quiz_question_student_answer_id',$quizQuestionStudentAnswer->id)
            ->first();
        if(!is_null($quizQuestionMatchingAnswer)){
            QuizQuestionMatchingAnswer::where('quiz_question_student_answer_id',$quizQuestionStudentAnswer->id)
                ->delete();
        }



        $correctAnswer = true;
        $mark = $quizQuestion->mark;
        foreach ($answerObject['matching'] as $matchingObject){
            $answerStatus = true;//for each blank
            $leftFromData = $leftLists->where('id',$matchingObject['left_list_id'])->first();
            //if one failed that mean the all answer on this question its invalid

            if(is_null($leftFromData) || $leftFromData->RightListRecords[0]->id != $matchingObject['right_list_id'] ){
                $correctAnswer = false;
                $mark = 0;
                $answerStatus = false;
            }
            if(!is_null($matchingObject['left_list_id']) && !is_null($matchingObject['right_list_id'])){
                QuizQuestionMatchingAnswer::create([
                    'quiz_question_student_answer_id' => $quizQuestionStudentAnswer->id,
                    'left_list_id' => $matchingObject['left_list_id'],
                    'right_list_id' => $matchingObject['right_list_id'],
                    'answer_status' => $answerStatus,
                ]);
            }


        }


        //if the student missed order all texts
        if(count($answerObject['matching']) < count($leftLists)){
            $correctAnswer = false;
            $mark = 0;
        }


        $quizQuestionStudentAnswer->update([
            'answer_status' => $correctAnswer,
            'mark' => $mark,
        ]);

//        $answerObject['choice_id'];
    }






}
