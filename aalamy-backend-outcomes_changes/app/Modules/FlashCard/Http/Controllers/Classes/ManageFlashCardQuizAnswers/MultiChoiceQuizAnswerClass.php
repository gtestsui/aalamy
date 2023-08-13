<?php


namespace Modules\FlashCard\Http\Controllers\Classes\ManageFlashCardQuizAnswers;


use Modules\FlashCard\Http\Controllers\Classes\FlashCardServices;
use Modules\FlashCard\Models\Card;
use Modules\FlashCard\Models\FlashCard;
use Modules\FlashCard\Models\MultiChoiceChoice;
use Modules\FlashCard\Models\MultiChoiceQuestion;

class MultiChoiceQuizAnswerClass
{

    private FlashCard $flashCard;
    private array $mapOfUserAnswers = [];//key is question_id and value is selected choice_id by the user
    private int $correctQuestionCount;
    public function __construct(FlashCard $flashCard,array $studentAnswers,&$correctQuestionCount)
    {
        $this->flashCard = $flashCard;
        $this->correctQuestionCount = $correctQuestionCount;
        $this->getStudentAnswersAsMap($studentAnswers);

    }


    /**
     * this will return collection of question with its choices and the student actions
     * on each question or choice(as select choice,answer on question or doesn't);
     * @return MultiChoiceQuestion
     */
    public function prepareResult(){
        $allQuestionsByFlashCard = $this->getAllQuestionByFlashCardIdWithData();
        foreach ($allQuestionsByFlashCard as  $question){
            $selectedChoiceId = $this->getStudentAnswerOnQuestion($question->id);

            $question->student_has_answered = false;
            foreach ($question->Choices as $choice){
                $choiceClass = new ChoiceClass($choice);
                if($choiceClass->itsSame($selectedChoiceId)){
                    $choiceClass->markAsSelected();
                    $question->student_has_answered = true;
                }

                if($choiceClass->itsCorrectChoice($question)){
                    $choiceClass->markAsCorrectChoice();
                    if($choiceClass->itsSelected())
                        $this->correctQuestionCount++;
                }
            }

        }
        return $allQuestionsByFlashCard;
    }


    private function getAllQuestionByFlashCardIdWithData(){
        $allQuestionsByFlashCard = MultiChoiceQuestion::where('flash_card_id',$this->flashCard->id)
            ->with('Card','Choices')
            ->get();
        return $allQuestionsByFlashCard;
    }

    private function getStudentAnswersAsMap(array $studentAnswers){
        foreach ($studentAnswers as $answer){
            $this->mapOfUserAnswers[$answer['question_id']] = (int)$answer['choice_id'];
        }
        return $this->mapOfUserAnswers;
    }


    private function getStudentAnswerOnQuestion($questionId){
        return isset($this->mapOfUserAnswers[$questionId])
            ?$this->mapOfUserAnswers[$questionId]
            :null;
    }




}
