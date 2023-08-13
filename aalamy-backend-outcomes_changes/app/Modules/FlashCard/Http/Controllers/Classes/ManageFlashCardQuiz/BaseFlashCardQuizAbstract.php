<?php


namespace Modules\FlashCard\Http\Controllers\Classes\ManageFlashCardQuiz;


use App\Exceptions\ErrorMsgException;
use App\Http\Controllers\Classes\ApplicationModules;
use Illuminate\Database\Eloquent\Model;
use Modules\FlashCard\Models\Card;
use Modules\FlashCard\Models\FlashCard;

class BaseFlashCardQuizAbstract
{

    protected FlashCard $flashCard;
    protected ?int $quizQuestionsNum;
    protected ?Model $modelQueryByQuizType;

    /**
     * check if the $quizQuestionsNum is null then set random number
     * @param mixed int|null $quizQuestionsNum
     * @return int
     * @throws ErrorMsgException
     */
    protected function prepareQuizQuestionsNum(?int $quizQuestionsNum):int
    {
        $maxCardsCount = Card::where('flash_card_id',$this->flashCard->id)
            ->count();
        if($maxCardsCount<2)
            throw new ErrorMsgException(transMsg('cant_create_quiz_with_less_than_2_cards',ApplicationModules::FLASH_CARD_MODULE_NAME));

        if(is_null($quizQuestionsNum))
            $quizQuestionsNum = random_int(config('FlashCard.panel.min_number_of_question'),$maxCardsCount);
        if($maxCardsCount<$quizQuestionsNum)
            throw new ErrorMsgException(transMsg('questions_number_smaller_than_cards',ApplicationModules::FLASH_CARD_MODULE_NAME));
        return $quizQuestionsNum;
    }


    public function generateQuiz(){

        $this->cleanOldQuizIfExist();

        $cards = $this->getTargetCards();

        foreach ($cards as $card){
            $question = $this->prepareQuestion($card->id);
        }

    }

    protected function cleanOldQuizIfExist(){
        $questions = $this->getExistedQuizQuestion();
        if(count($questions)>0)
            $this->deleteOldQuiz($questions);

    }

    public function getExistedQuizQuestion(){
        $questions = $this->modelQueryByQuizType
            ->where('flash_card_id',$this->flashCard->id)
            ->get();
        return $questions;
    }

    protected function getTargetCards(){
        return Card::where('flash_card_id',$this->flashCard->id)
            ->inRandomOrder()->limit($this->quizQuestionsNum)->get();
    }


    public function reGenerateQuizIfExistedBefore(){
        $questions = $this->getExistedQuizQuestion();
        if(count($questions)==0)
            return true;
        $this->deleteOldQuiz($questions);
        $this->generateQuiz();
    }




    public function deleteOldQuiz($questions){
        $questionIds = $questions->pluck('id')->toArray();

        $this->modelQueryByQuizType
            ->whereIn('id',$questionIds)
            ->delete();
    }





}
