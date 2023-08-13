<?php

namespace Modules\QuestionBank\Http\Resources\QuestionLibrary;;

use App\Http\Traits\PaginationResources;
use Illuminate\Http\Resources\Json\JsonResource;
use Modules\QuestionBank\Traits\BaseQuestionTypesResources\BaseMatchingRightListResource;

class QuestionLibraryMatchingRightListResource extends JsonResource
{
    use PaginationResources;

    /**
     * get the shared resource between the question in library and in bank
     */
    use BaseMatchingRightListResource;

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
            'library_question_id' => (int)$this->library_question_id,

            'question' => new QuestionLibraryResource($this->whenLoaded('Question')),
            'left_list' => new QuestionLibraryMatchingLeftListResource($this->whenLoaded('LeftList')),

        ]);
    }
}
