<?php

namespace Modules\FlashCard\Http\Resources;

use App\Http\Traits\PaginationResources;
use Illuminate\Http\Resources\Json\JsonResource;

class MultiChoiceQuestionResource extends JsonResource
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
//            'card_id' => $this->card_id,
            'question' => $this->Card->question,
            'student_has_answered' => $this->when(isset($this->student_has_answered),(bool)$this->student_has_answered),
            'choices' => MultiChoiceChoicesResource::collection(
                $this->whenLoaded('Choices')
            ),
//        'created_at' => $this->>created_at,

        ];
    }
}
