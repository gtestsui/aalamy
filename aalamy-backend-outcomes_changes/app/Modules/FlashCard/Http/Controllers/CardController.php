<?php

namespace Modules\FlashCard\Http\Controllers;

use App\Http\Controllers\Classes\ApiResponseClass;
use App\Http\Controllers\Controller;
use Modules\FlashCard\Http\DTO\CardData;
use Modules\FlashCard\Http\Requests\Card\DestroyCardRequest;
use Modules\FlashCard\Http\Requests\Card\StoreCardRequest;
use Modules\FlashCard\Http\Requests\Card\UpdateCardRequest;
use Modules\FlashCard\Http\Resources\CardResource;
use Modules\FlashCard\Models\Card;

class CardController extends Controller
{

    public function store(StoreCardRequest $request,$flash_card_id){
        $user = $request->user();
        $cardData = CardData::fromRequest($request,$flash_card_id);
        $card = Card::create($cardData->all());
        return ApiResponseClass::successResponse(new CardResource($card));
    }


    public function update(UpdateCardRequest $request,$id){
        $user = $request->user();
        $card = $request->getCard();
        $cardData = CardData::fromRequest($request,$card->flash_card_id);
        $card->update($cardData->initializeForUpdate($cardData));
        return ApiResponseClass::successResponse(new CardResource($card));

    }

    public function destroy(DestroyCardRequest $request,$id){
        $user = $request->user();
        $card = $request->getCard();
        $card->delete();
        return ApiResponseClass::deletedResponse();
    }


}
