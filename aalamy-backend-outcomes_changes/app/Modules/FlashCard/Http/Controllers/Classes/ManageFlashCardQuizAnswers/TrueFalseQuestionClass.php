<?php


namespace Modules\FlashCard\Http\Controllers\Classes\ManageFlashCardQuizAnswers;


use Modules\FlashCard\Models\TrueFalseQuestion;

class TrueFalseQuestionClass
{

    private TrueFalseQuestion $question;
    private ?bool $studentAnswer;
    public function __construct(TrueFalseQuestion &$question,?bool $studentAnswer=null)
    {
        $this->question = $question;
        $this->studentAnswer = $studentAnswer;
    }




    public function proccessStudentAnswer(){
        if($this->answerShouldBeTrue()){
            $this->question->correct_answer_is = true;//the question correct answer
            $this->question->student_answer_status = $this->studentAnswer===true?true:false;
        }else{
            $this->question->correct_answer_is = false;//the question correct answer
            $this->question->student_answer_status = $this->studentAnswer===false?true:false;
        }
        return $this->question->student_answer_is;
    }

    public function answerShouldBeTrue(){
        if($this->question->question_card_id == $this->question->answer_card_id)
            return true;
        return false;
    }


    public function setStudentAnswer(){
        $this->question->student_answer_status = $this->studentAnswer;
    }





}
