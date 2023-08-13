<?php

namespace Modules\Quiz\Http\Controllers\Classes\ManageQuizQuestionStudentAnswer;

use App\Exceptions\ErrorMsgException;
use Modules\Quiz\Models\QuizQuestion;
use Modules\Quiz\Models\QuizQuestionFillInBlankAnswer;
use Modules\Quiz\Models\QuizQuestionStudentAnswer;

class FillInBlankAnswerClassManagement extends BaseManageQuestionAbstract
{



    public function checkAnswer(QuizQuestion $quizQuestion,$answerObject,QuizQuestionStudentAnswer &$quizQuestionStudentAnswer){
        $fillInBlanks = $quizQuestion->QuestionBank->FillInBlanks;
        if(empty($fillInBlanks))
            throw new ErrorMsgException('invalid question type with answers');


        if(!isset($answerObject['fill_in_blanks'])){
            $quizQuestionStudentAnswer->update([
                'answer_status' => false,
                'mark' => 0,
            ]);
            return;
        }
        $quizQuestionFillInBlankAnswer = QuizQuestionFillInBlankAnswer::where('quiz_question_student_answer_id',$quizQuestionStudentAnswer->id)
            ->first();
        if(!is_null($quizQuestionFillInBlankAnswer)){
            QuizQuestionFillInBlankAnswer::where('quiz_question_student_answer_id',$quizQuestionStudentAnswer->id)
                ->delete();
        }

        $correctAnswer = true;
        $mark = $quizQuestion->mark;
        foreach ($answerObject['fill_in_blanks'] as $fillInBlankObject){
            $answerStatus = true;//for each blank
            $fillInBlankFromData = $fillInBlanks->where('word',$fillInBlankObject['word'])->first();
            if(is_null($fillInBlankFromData) || $fillInBlankFromData->order != $fillInBlankObject['order'] ){
                $correctAnswer = false;
                $mark = 0;
                $answerStatus = false;
            }

            if(!is_null($fillInBlankObject['word'])){
                QuizQuestionFillInBlankAnswer::create([
                    'quiz_question_student_answer_id' => $quizQuestionStudentAnswer->id,
                    'word' => $fillInBlankObject['word'],
                    'order' => $fillInBlankObject['order'],
                    'answer_status' => $answerStatus,
                ]);
            }

        }

        //if the student missed one blank so answer it's not correct
        if(count($answerObject['fill_in_blanks']) < count($fillInBlanks)){
            $correctAnswer = false;
            $mark = 0;
        }

        $quizQuestionStudentAnswer->update([
            'answer_status' => $correctAnswer,
            'mark' => $mark,
        ]);

    }





}
