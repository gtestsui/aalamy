<?php

namespace Modules\Quiz\Http\Controllers\Classes\ManageQuizQuestionStudentAnswer;

use App\Exceptions\ErrorMsgException;
use Modules\Quiz\Models\QuizQuestion;
use Modules\Quiz\Models\QuizQuestionFillTextAnswer;
use Modules\Quiz\Models\QuizQuestionStudentAnswer;

class FillTextAnswerClassManagement extends BaseManageQuestionAbstract
{

    public function checkAnswer(QuizQuestion $quizQuestion,$answerObject,QuizQuestionStudentAnswer &$quizQuestionStudentAnswer){
        $fillTexts = $quizQuestion->QuestionBank->FillTexts;
        $texts = $fillTexts->pluck('text')->toArray();
        if(empty($fillTexts))
            throw new ErrorMsgException('invalid question type with answers');

        if(!isset($answerObject['fill_text'])){
            $quizQuestionStudentAnswer->update([
                'answer_status' => false,
                'mark' => 0,
            ]);
            return;
        }

        $quizQuestionFillTextAnswer = QuizQuestionFillTextAnswer::where('quiz_question_student_answer_id',$quizQuestionStudentAnswer->id)
            ->first();
        if(!is_null($quizQuestionFillTextAnswer)){
            QuizQuestionFillTextAnswer::where('quiz_question_student_answer_id',$quizQuestionStudentAnswer->id)
                ->delete();
        }

        $correctAnswer = in_array($answerObject['fill_text'],$texts)?true:false;
        $mark = in_array($answerObject['fill_text'],$texts)?$quizQuestion->mark:0;

        QuizQuestionFillTextAnswer::create([
            'quiz_question_student_answer_id' => $quizQuestionStudentAnswer->id,
            'text' => $answerObject['fill_text'],
        ]);

        $quizQuestionStudentAnswer->update([
            'answer_status' => $correctAnswer,
            'mark' => $mark,
        ]);

    }




}
