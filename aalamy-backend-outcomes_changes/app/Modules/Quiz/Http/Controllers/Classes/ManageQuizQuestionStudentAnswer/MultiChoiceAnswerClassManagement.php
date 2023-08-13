<?php

namespace Modules\Quiz\Http\Controllers\Classes\ManageQuizQuestionStudentAnswer;

use App\Exceptions\ErrorMsgException;
use Modules\Quiz\Models\QuizQuestionMultiChoiceAnswer;
use Modules\Quiz\Models\QuizQuestion;
use Modules\Quiz\Models\QuizQuestionStudentAnswer;

class MultiChoiceAnswerClassManagement extends BaseManageQuestionAbstract
{



    public function checkAnswer(QuizQuestion $quizQuestion,$answerObject,QuizQuestionStudentAnswer &$quizQuestionStudentAnswer){
        $multiChoices = $quizQuestion->QuestionBank->MultiChoices;

        if(empty($multiChoices))
            throw new ErrorMsgException('invalid question type with answers');

        if(!isset($answerObject['choice_id'])){
            $quizQuestionStudentAnswer->update([
                'answer_status' => false,
                'mark' => 0,
            ]);
            return;
        }


        //we have make this because the student can answer question ,question so he can update answers
        $quizQuestionMultiChoiceAnswer = QuizQuestionMultiChoiceAnswer::where('quiz_question_student_answer_id',$quizQuestionStudentAnswer->id)
            ->first();
        if(!is_null($quizQuestionMultiChoiceAnswer)){
            QuizQuestionMultiChoiceAnswer::where('quiz_question_student_answer_id',$quizQuestionStudentAnswer->id)
                ->delete();
        }

        $selectedChoice = $multiChoices->where('id',$answerObject['choice_id'])->first();

        QuizQuestionMultiChoiceAnswer::create([
            'quiz_question_student_answer_id' => $quizQuestionStudentAnswer->id,
            'choice_id' => $selectedChoice->id,
        ]);

        $quizQuestionStudentAnswer->update([
            'answer_status' => $selectedChoice->status,
            'mark' => $selectedChoice->status?$quizQuestion->mark:0,
        ]);

    }


}
