<?php

namespace Modules\FlashCard\Http\Resources;

use App\Http\Traits\PaginationResources;
use Illuminate\Http\Resources\Json\JsonResource;

class TrueFalseQuestionResource extends JsonResource
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
            'question' => $this->QuestionCard->question.' '.$this->AnswerCard->answer,
            'correct_answer_is' => $this->when(isset($this->correct_answer_is),(bool)$this->correct_answer_is),
//            'student_has_answered' => $this->when(isset($this->student_has_answered),(bool)$this->student_has_answered),
            'student_answer_status' => $this->when(isset($this->student_answer_status),(bool)$this->student_answer_status),

//            'question_card_id' => $this->question_card_id,
//            'answer_card_id' => $this->answer_card_id,
//        'created_at' => $this->>created_at,

        ];
    }
}
