<?php

namespace Modules\QuestionBank\Http\Resources\QuestionBank;

use App\Http\Traits\PaginationResources;
use Illuminate\Http\Resources\Json\JsonResource;
use Modules\QuestionBank\Traits\BaseQuestionTypesResources\BaseMatchingLeftListResource;

class QuestionBankMatchingLeftListResource extends JsonResource
{
    use PaginationResources;

    /**
     * get the shared resource between the question in library and in bank
     */
    use BaseMatchingLeftListResource;


    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $sharedResources = $this->baseResource();

        return array_merge($sharedResources,[
            'question_id' => (int)$this->question_id,

            'question' => new QuestionBankResource($this->whenLoaded('Question')),
            'right_list_records' => QuestionBankMatchingRightListResource::collection($this->whenLoaded('RightListRecords')),

        ]);
    }
}
