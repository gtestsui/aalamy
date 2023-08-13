<?php


namespace Modules\FlashCard\Http\DTO;


use App\Http\Controllers\DTO\Parents\ObjectData;
use Illuminate\Http\Request;
use Modules\User\Http\Controllers\Classes\UserServices;
use Modules\User\Models\Teacher;
use Modules\User\Models\User;
use Carbon\Carbon;

final class CardData extends ObjectData
{
    public ?int       $id=null;
    public int        $flash_card_id;
    public string     $question ;
    public string     $answer ;
////    public ?Carbon   $created_at;

    public static function fromRequest(Request $request,$flash_card_id): self
    {

        return new self([
            'flash_card_id' => (int)$flash_card_id,
            'question' => $request->question,
            'answer' => $request->answer,

        ]);
    }


}
