<?php

namespace Modules\Quiz\Http\Controllers\Classes\ManageQuizQuestionStudentAnswer;

use App\Exceptions\ErrorMsgException;
use Modules\QuestionBank\Http\DTO\QuestionBankData;
use Modules\QuestionBank\Models\LibraryQuestion;
use Modules\QuestionBank\Models\LibraryQuestionTrueFalse;
use Modules\QuestionBank\Models\QuestionBank;
use Modules\QuestionBank\Models\QuestionBankTrueFalse;
use Modules\Quiz\Models\QuizQuestion;
use Modules\Quiz\Models\QuizQuestionStudentAnswer;
use Modules\Quiz\Models\QuizQuestionTrueFalseAnswer;

class TrueFalseAnswerClassManagement extends BaseManageQuestionAbstract
{



    public function checkAnswer(QuizQuestion $quizQuestion,$answerObject,QuizQuestionStudentAnswer &$quizQuestionStudentAnswer){
        $trueFalseObject = $quizQuestion->QuestionBank->TrueFalse;
        if(empty($trueFalseObject))
            throw new ErrorMsgException('invalid question type with answers');


        if(!isset($answerObject['true_false_status'])){
            $quizQuestionStudentAnswer->update([
                'answer_status' => false,
                'mark' => 0,
            ]);
            return;
        }

        //we have make this because the student can answer question ,question so he can update answers
        $quizQuestionTrueFalseAnswer = QuizQuestionTrueFalseAnswer::where('quiz_question_student_answer_id',$quizQuestionStudentAnswer->id)
            ->first();
        if(!is_null($quizQuestionTrueFalseAnswer)){
            QuizQuestionTrueFalseAnswer::where('quiz_question_student_answer_id',$quizQuestionStudentAnswer->id)
                ->delete();
        }

        $correctAnswer = $trueFalseObject->status == $answerObject['true_false_status']?true:false;
        $mark = $correctAnswer?$quizQuestion->mark:0;
        QuizQuestionTrueFalseAnswer::create([
                'quiz_question_student_answer_id' => $quizQuestionStudentAnswer->id,
                'chosen_status' => $answerObject['true_false_status'],
        ]);


        $quizQuestionStudentAnswer->update([
            'answer_status' => $correctAnswer,
            'mark' => $mark,
        ]);


    }









    public function createInBank(QuestionBank $question,QuestionBankData $questionData){
        QuestionBankTrueFalse::create([
            'question_id' => $question->id,
            'status' => $questionData->true_false_status,
        ]);
    }

    public function updateInBank(QuestionBank $question,QuestionBankData $questionData){
        $questionBankTrueFalse = QuestionBankTrueFalse::where('question_id',$question->id)
            ->first();
        $questionBankTrueFalse->update([
            'status' => $questionData->true_false_status
        ]);
    }

    /**
     * @note this is abstract function from parent
     */
    public function getMyQuestionType(){
        return  config('QuestionBank.panel.question_types.true_false');
    }

    public function shareWithLibrary(QuestionBank $question,string $shareType){
        $libraryQuestion = $this->storeInLibraryBySharing($question,$shareType);
        $trueFalseRecord = $question->TrueFalse;
        $this->createInLibrary($libraryQuestion,$trueFalseRecord);

        /*LibraryQuestionTrueFalse::create([
            'library_question_id' => $libraryQuestion->id,
            'status' => $trueFalseRecord->status,
        ]);*/

    }

    public function createInLibrary(LibraryQuestion $libraryQuestion,$trueFalseRecord){
        LibraryQuestionTrueFalse::create([
            'library_question_id' => $libraryQuestion->id,
            'status' => $trueFalseRecord->status,
        ]);
    }

    /**
     * @param LibraryQuestion|QuestionBank $question
     */
    public function load( $question){
        return $question->load('TrueFalse');
    }

}
