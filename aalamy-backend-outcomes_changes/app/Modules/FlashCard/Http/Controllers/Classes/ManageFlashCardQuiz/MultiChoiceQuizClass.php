<?php


namespace Modules\FlashCard\Http\Controllers\Classes\ManageFlashCardQuiz;


use Modules\FlashCard\Http\Controllers\Classes\FlashCardServices;
use Modules\FlashCard\Models\Card;
use Modules\FlashCard\Models\FlashCard;
use Modules\FlashCard\Models\MultiChoiceQuestion;

class MultiChoiceQuizClass extends BaseFlashCardQuizAbstract
{

    private int $quizQuestionChoicesNum;
    public function __construct(FlashCard $flashCard,?int $quizQuestionsNum=null,?int $quizQuestionChoicesNum=null)
    {
        $this->flashCard = $flashCard;
        $this->quizQuestionsNum = $this->prepareQuizQuestionsNum($quizQuestionsNum);
        $this->quizQuestionChoicesNum = $this->prepareQuizQuestionChoicesNum($quizQuestionChoicesNum);
        $this->modelQueryByQuizType = MultiChoiceQuestion::query();
    }

    /**
     * check if the $quizQuestionChoicesNum is null then set default number
     * @param mixed int|null $quizQuestionChoicesNum
     * @return int
     */
    private function prepareQuizQuestionChoicesNum(?int $quizQuestionChoicesNum):int
    {
        if(is_null($quizQuestionChoicesNum))
            return 4;
        return $quizQuestionChoicesNum;

    }

    private function prepareQuestion(int $cardId):MultiChoiceQuestion
    {
        $question = MultiChoiceQuestion::create([
            'flash_card_id' => $this->flashCard->id,
            'card_id' => $cardId,
        ]);

        $this->prepareChoices($question);
        return $question;
    }

    private function prepareChoices(MultiChoiceQuestion $question){
        //get valid card ids
        $cardsIds = Card::where('id','!=',$question->card_id)
            ->where('flash_card_id',$question->flash_card_id)
            ->inRandomOrder()
            ->limit($this->quizQuestionChoicesNum-1)
            ->pluck('id')->toArray();
        //add the correct answer to choices
        $cardsIds[] = $question->card_id;
        //shuffle the choices
        shuffle($cardsIds);
        FlashCardServices::addMoreThanMultiChoiceChoice($question->id,$cardsIds);
    }

}
