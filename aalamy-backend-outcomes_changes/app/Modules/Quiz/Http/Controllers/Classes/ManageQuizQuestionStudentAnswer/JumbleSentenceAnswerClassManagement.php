<?php

namespace Modules\Quiz\Http\Controllers\Classes\ManageQuizQuestionStudentAnswer;

use App\Exceptions\ErrorMsgException;
use Modules\Quiz\Models\QuizQuestion;
use Modules\Quiz\Models\QuizQuestionJumbleSentenceAnswer;
use Modules\Quiz\Models\QuizQuestionStudentAnswer;

class JumbleSentenceAnswerClassManagement extends BaseManageQuestionAbstract
{



    public function checkAnswer(QuizQuestion $quizQuestion,$answerObject,QuizQuestionStudentAnswer &$quizQuestionStudentAnswer){
        $jumbleSentences = $quizQuestion->QuestionBank->JumbleSentences;
        if(empty($jumbleSentences))
            throw new ErrorMsgException('invalid question type with answers');


        if(!isset($answerObject['jumble_sentence'])){
            $quizQuestionStudentAnswer->update([
                'answer_status' => false,
                'mark' => 0,
            ]);
            return;
        }


        $quizQuestionJumbleSentenceAnswer = QuizQuestionJumbleSentenceAnswer::where('quiz_question_student_answer_id',$quizQuestionStudentAnswer->id)
            ->first();
        if(!is_null($quizQuestionJumbleSentenceAnswer)){
            QuizQuestionJumbleSentenceAnswer::where('quiz_question_student_answer_id',$quizQuestionStudentAnswer->id)
                ->delete();
        }

        $correctAnswer = true;
        $mark = $quizQuestion->mark;
        foreach ($answerObject['jumble_sentence'] as $jumbleSentenceObject){
            $answerStatus = true;//for each blank
            $jumbleSentenceFromData = $jumbleSentences->where('id',$jumbleSentenceObject['jumble_sentence_id'])->first();

            if (is_null($jumbleSentenceFromData) || $jumbleSentenceFromData->order != $jumbleSentenceObject['order'] ){
                $correctAnswer = false;
                $mark = 0;
                $answerStatus = false;
            }
            if(!is_null($jumbleSentenceObject['jumble_sentence_id'])){
                QuizQuestionJumbleSentenceAnswer::create([
                    'quiz_question_student_answer_id' => $quizQuestionStudentAnswer->id,
                    'jumble_sentence_id' => $jumbleSentenceObject['jumble_sentence_id'],
                    'order' => $jumbleSentenceObject['order'],
                    'answer_status' => $answerStatus,
                ]);
            }

        }

        //if the student missed on blank so answer it's not correct
        if(count($answerObject['jumble_sentence']) < count($jumbleSentences)){
            $correctAnswer = false;
            $mark = 0;
        }

        $quizQuestionStudentAnswer->update([
            'answer_status' => $correctAnswer,
            'mark' => $mark,
        ]);

    }








}
