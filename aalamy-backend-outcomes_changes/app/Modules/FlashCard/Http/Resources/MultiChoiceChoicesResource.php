<?php

namespace Modules\FlashCard\Http\Resources;

use App\Http\Traits\PaginationResources;
use Illuminate\Http\Resources\Json\JsonResource;

class MultiChoiceChoicesResource extends JsonResource
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
            'multi_choice_question_id' => isset($this->multi_choice_question_id)?(int)$this->multi_choice_question_id:$this->multi_choice_question_id,
//            'card_id' => $this->card_id,
            'selected_by_student' => $this->when(isset($this->selected_by_student),(bool)$this->selected_by_student),
            'correct' => $this->when(isset($this->correct),(bool)$this->correct),
            'choice' => $this->Card->answer,
//        'created_at' => $this->>created_at,

        ];
    }
}
