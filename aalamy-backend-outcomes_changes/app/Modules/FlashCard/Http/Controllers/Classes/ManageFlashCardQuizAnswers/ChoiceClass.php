<?php


namespace Modules\FlashCard\Http\Controllers\Classes\ManageFlashCardQuizAnswers;


use Modules\FlashCard\Models\MultiChoiceChoice;

class ChoiceClass
{

    private MultiChoiceChoice $choice;
    public function __construct(MultiChoiceChoice &$choice)
    {
        $this->choice = $choice;
        $this->markAsSelected(false);
        $this->markAsCorrectChoice(false);

    }


    public function itsSame($selectedChoiceId){
        if($this->choice->id == $selectedChoiceId)
            return true;
        return false;
    }

    public function itsCorrectChoice($question){
        if($this->choice->card_id == $question->card_id)
            return true;
        return false;
    }

    public function markAsSelected(bool $status=true){
        $this->choice->selected_by_student = $status;
    }

    public function markAsCorrectChoice(bool $status=true){
        $this->choice->correct = $status;
    }

    public function itsSelected(){
        return (bool)$this->choice->selected_by_student;
    }





}
