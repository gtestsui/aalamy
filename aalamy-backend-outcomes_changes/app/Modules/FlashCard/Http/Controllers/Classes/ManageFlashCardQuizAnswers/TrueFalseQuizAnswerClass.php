<?php


namespace Modules\FlashCard\Http\Controllers\Classes\ManageFlashCardQuizAnswers;


use Modules\FlashCard\Models\Card;
use Modules\FlashCard\Models\FlashCard;
use Modules\FlashCard\Models\TrueFalseQuestion;

class TrueFalseQuizAnswerClass
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

    public function getAllQuestionByFlashCardIdWithData(){
        $allQuestionsByFlashCard = TrueFalseQuestion::where('flash_card_id',$this->flashCard->id)
            ->with('QuestionCard','AnswerCard')
            ->get();
        return $allQuestionsByFlashCard;
    }


    private function getStudentAnswersAsMap(array $studentAnswers){
        foreach ($studentAnswers as $answer){
            $this->mapOfUserAnswers[$answer['question_id']] = (bool)$answer['answer'];
        }
        return $this->mapOfUserAnswers;
    }

    private function getStudentAnswerOnQuestion($questionId){
        return isset($this->mapOfUserAnswers[$questionId])
            ?$this->mapOfUserAnswers[$questionId]
            :null;
    }


    /**
     * this will return collection of question with the student actions
     * on each question (as select this question as true or false);
     * @return TrueFalseQuestion
     */
    public function prepareResult(){
        $allQuestionsByFlashCard = $this->getAllQuestionByFlashCardIdWithData();
        foreach ($allQuestionsByFlashCard as  $question) {
            $studentAnswer = $this->getStudentAnswerOnQuestion($question->id);
            $questionClass = new TrueFalseQuestionClass($question, $studentAnswer);

            $studentAnswerStatus = $questionClass->proccessStudentAnswer();
            if ($studentAnswerStatus)
                $this->correctQuestionCount++;
        }
        return $allQuestionsByFlashCard;


    }



}
