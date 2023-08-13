<?php

namespace Modules\FlashCard\Http\Resources;

use App\Http\Traits\PaginationResources;
use Illuminate\Http\Resources\Json\JsonResource;
use Modules\Assignment\Http\Resources\AssignmentResource;

class CardResource extends JsonResource
{
    use PaginationResources;


    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'flash_card_id' => isset($this->flash_card_id)?(int)$this->flash_card_id:$this->flash_card_id,
            'question' => $this->question,
            'answer' => $this->answer,
            'flash_card' => new FlashCardResource($this->whenLoaded('FlashCard')),

        ];
    }
}
