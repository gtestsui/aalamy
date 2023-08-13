<?php

namespace Modules\FlashCard\Http\Controllers\Classes;


use Carbon\Carbon;
use Modules\FlashCard\Http\Controllers\Classes\ManageQuizHint\FlashCardQuizManagementFactory;
use Modules\FlashCard\Http\DTO\FlashCardData;
use Modules\FlashCard\Models\Card;
use Modules\FlashCard\Models\FlashCard;

class CardClass
{

    public function addMoreThanCardToFlashCard(FlashCard $flashCard,?array $cards){
        if(count($cards)>0){

            $cardArrayForCreate = [];
            foreach ($cards as $card){
                $cardArrayForCreate [] =[
                    'flash_card_id' =>  $flashCard->id,
                    'question' =>  $card['question'],
                    'answer' =>  $card['answer'],
                    'created_at' =>  Carbon::now(),
                ];
            }
            Card::insert($cardArrayForCreate);

        }
    }

    public function addCardToFlashCard(FlashCard $flashCard,$question,$answer){
        return Card::create([
            'flash_card_id' =>  $flashCard->id,
            'question' =>  $question,
            'answer' =>  $answer,
        ]);
    }

   /* public function deleteMoreThanImageFromUserGuide(?array $imageIds){
        if(isset($imageIds)){
            foreach ($imageIds as $imageId){
                $this->deleteImageFromUserGuide($imageId);
            }
        }
    }

    public function deleteImageFromUserGuide($imageId){
        return HelpCenterUserGuideImage::find($imageId)->delete();
    }*/

   public function updateMoreThanCardInFlashCard(FlashCard $flashCard,?array $cards,FlashCardData $flashCardData/*?bool $generateQuiz=null*/){
       if(count($cards)>0){
           $keepIdsAwayFromDelete = [];
           foreach ($cards as $card){
               $foundCard = Card::where('flash_card_id',$flashCard->id)
                   ->where('question',$card['question'])
                   ->where('answer',$card['answer'])->first();
               if(is_null($foundCard)){
                   $foundCard = Self::addCardToFlashCard($flashCard,$card['question'],$card['answer']);

               }
               $keepIdsAwayFromDelete[] = $foundCard->id;
           }
           Card::where('flash_card_id',$flashCard->id)
               ->whereNotIn('id',$keepIdsAwayFromDelete)->delete();
           foreach ($flashCardData->quiz_types as $quizType){
               // if he had generated quiz before regenerate it
               //if he didnt generate before check if he want to generate new one
//               $manageQuizClass = FlashCardServices::createManageQuizClassByQuizType(
//                   $quizType,$flashCard,$flashCardData
//               );
               $manageQuizClass = FlashCardQuizManagementFactory::create(
                   $quizType,$flashCard,$flashCardData
               );
               $manageQuizClass->reGenerateQuizIfExistedBefore();
               if($flashCardData->generate_quiz)
                   $manageQuizClass->generateQuiz();
           }
       }
   }



}
