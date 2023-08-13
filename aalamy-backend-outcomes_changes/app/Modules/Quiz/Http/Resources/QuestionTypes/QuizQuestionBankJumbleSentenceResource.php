<?php

namespace Modules\Quiz\Http\Resources\QuestionTypes;

use App\Http\Traits\PaginationResources;
use Illuminate\Http\Resources\Json\JsonResource;
use Modules\QuestionBank\Traits\BaseQuestionTypesResources\BaseJumbleSentenceResource;
use Modules\Quiz\Http\Resources\QuizQuestionBankResource;

class QuizQuestionBankJumbleSentenceResource extends JsonResource
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
            'word' => $this->word,
            'question' => new QuizQuestionBankResource($this->whenLoaded('Question')),

        ];
    }
}
