<?php

namespace Modules\FlashCard\Http\Controllers;

use App\Http\Controllers\Classes\ApiResponseClass;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Modules\FlashCard\Http\Controllers\Classes\CardClass;
use Modules\FlashCard\Http\Controllers\Classes\ManageQuizHint\FlashCardQuizManagementFactory;
use Modules\FlashCard\Http\DTO\FlashCardData;
use Modules\FlashCard\Http\Requests\FlashCard\DestroyFlashCardRequest;
use Modules\FlashCard\Http\Requests\FlashCard\GetFlashCardByAssignmentIdRequest;
use Modules\FlashCard\Http\Requests\FlashCard\StoreFlashCardRequest;
use Modules\FlashCard\Http\Requests\FlashCard\UpdateFlashCardRequest;
use Modules\FlashCard\Http\Resources\FlashCardResource;
use Modules\FlashCard\Models\FlashCard;

class FlashCardController extends Controller
{


    public function getByAssignmentId(GetFlashCardByAssignmentIdRequest $request,$assignment_id){
        $user = $request->user();
        $flashCards = FlashCard::where('assignment_id',$assignment_id)
            ->with(['Cards','MultiChoiceQuestions'=>function($query){
                return $query->with('Choices');
            },'TrueFalseQuestions'])
            ->get();
        return ApiResponseClass::successResponse(FlashCardResource::collection($flashCards));

    }

    public function store(StoreFlashCardRequest $request){
        $user = $request->user();
        DB::beginTransaction();
        $flashCardData = FlashCardData::fromRequest($request);
        $flashCard = FlashCard::create($flashCardData->all());
        $cardClass = new CardClass();
        $cardClass->addMoreThanCardToFlashCard($flashCard,$flashCardData->cards);

        if($flashCardData->generate_quiz){
            foreach ($flashCardData->quiz_types as $quizType){
//                $manageQuizClass = FlashCardServices::createManageQuizClassByQuizType(
//                    $quizType,
//                    $flashCard,
//                    $flashCardData
//                );
                $manageQuizClass = FlashCardQuizManagementFactory::create(
                    $quizType,$flashCard,$flashCardData
                );
                $manageQuizClass->generateQuiz();
            }
        }
        DB::commit();
        return ApiResponseClass::successResponse(new FlashCardResource($flashCard));
    }

    public function update(UpdateFlashCardRequest $request,$id){
        $user = $request->user();
        DB::beginTransaction();
        $flashCard = $request->getFlashCard();
        $flashCardData = FlashCardData::fromRequest($request);
        //update the flash card setting
        $flashCard->update($flashCardData->initializeForUpdate($flashCardData));
        //update cards (delete,create new one , update old one)
        $cardClass = new CardClass();
        $cardClass->updateMoreThanCardInFlashCard($flashCard,$flashCardData->cards,$flashCardData);

        DB::commit();
        return ApiResponseClass::successResponse(new FlashCardResource($flashCard));
    }

    public function softDelete(DestroyFlashCardRequest $request,$id){
        DB::beginTransaction();
        $user = $request->user();
        $flashCard = $request->getFlashCard();
        $flashCard->softDeleteObject();
        DB::commit();
        return ApiResponseClass::deletedResponse();
    }

    public function destroy(DestroyFlashCardRequest $request,$id){
        $user = $request->user();
        $flashCard = $request->getFlashCard();
        $flashCard->delete();
        return ApiResponseClass::deletedResponse();
    }


}
