<?php

namespace Modules\Quiz\Http\Resources\QuestionTypes;

use App\Http\Traits\PaginationResources;
use Illuminate\Http\Resources\Json\JsonResource;
use Modules\QuestionBank\Traits\BaseQuestionTypesResources\BaseOrderingResource;
use Modules\Quiz\Http\Resources\QuizQuestionBankResource;

class QuizQuestionBankOrderingResource extends JsonResource
{
    use PaginationResources;

    /**
     * get the shared resource between the question in library and in bank
     */


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
            'question_id' => (int)$this->question_id,
            'text' => $this->text,

            'question' => new QuizQuestionBankResource($this->whenLoaded('Question')),

        ];
    }
}
