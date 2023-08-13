<?php

namespace Modules\FlashCard\Http\Resources;

use App\Http\Traits\PaginationResources;
use Illuminate\Http\Resources\Json\JsonResource;
use Modules\Assignment\Http\Resources\AssignmentResource;

class FlashCardResource extends JsonResource
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
            'assignment_id' => isset($this->assignment_id)?(int)$this->assignment_id:$this->assignment_id,
            'display_time_in_seconds' => $this->display_time_in_seconds,
            'success_percentage' => (int)$this->success_percentage,
            'quiz_time' => (int)$this->quiz_time,
            'quiz_time_type' => $this->quiz_time_type,
            'assignment' => new AssignmentResource($this->whenLoaded('Assignment')),
            'cards' => CardResource::collection($this->whenLoaded('Cards')),
            'multi_choice_questions' => MultiChoiceQuestionResource::collection(
              $this->whenLoaded('MultiChoiceQuestions')
            ),
            'true_false_questions' => TrueFalseQuestionResource::collection(
                $this->whenLoaded('TrueFalseQuestions')
            ),
        ];
    }
}
