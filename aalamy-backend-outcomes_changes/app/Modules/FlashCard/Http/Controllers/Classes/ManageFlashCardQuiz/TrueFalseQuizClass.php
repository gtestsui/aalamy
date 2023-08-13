<?php


namespace Modules\FlashCard\Http\Controllers\Classes\ManageFlashCardQuiz;


use App\Exceptions\ErrorMsgException;
use App\Http\Controllers\Classes\ApplicationModules;
use Modules\FlashCard\Models\Card;
use Modules\FlashCard\Models\FlashCard;
use Modules\FlashCard\Models\TrueFalseQuestion;

class TrueFalseQuizClass extends BaseFlashCardQuizAbstract
{

    public function __construct(FlashCard $flashCard,?int $quizQuestionsNum=null,?int $quizQuestionChoicesNum=null)
    {
        $this->flashCard = $flashCard;
        $this->quizQuestionsNum = $this->prepareQuizQuestionsNum($quizQuestionsNum);
        $this->modelQueryByQuizType = TrueFalseQuestion::query();

    }

    /**
     * generate random int between 0 and 1
     * to make the false percent of this question 50 and 50 for true
     * var $valid is 0 that meant the correct answer for question its false
     * else true
     */
    private function prepareQuestion(int $cardId):TrueFalseQuestion
    {
        $questionCardId = $cardId;
        $answerCardId = $cardId;
        $valid = random_int(0,1);
        if(!$valid)
            $answerCardId = Card::where('id','!=',$cardId)
                ->where('flash_card_id',$this->flashCard->id)
                ->inRandomOrder()
                ->first()->id;
        return TrueFalseQuestion::create([
            'flash_card_id' => $this->flashCard->id,
            'question_card_id' => $questionCardId,
            'answer_card_id' => $answerCardId,
        ]);
    }



}
