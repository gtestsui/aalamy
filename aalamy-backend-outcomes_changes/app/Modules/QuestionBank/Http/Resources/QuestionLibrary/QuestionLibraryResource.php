<?php

namespace Modules\QuestionBank\Http\Resources\QuestionLibrary;

use App\Http\Traits\PaginationResources;
use Illuminate\Http\Resources\Json\JsonResource;
use Modules\QuestionBank\Traits\BaseQuestionTypesResources\BaseQuestionResource;

class QuestionLibraryResource extends JsonResource
{
    use PaginationResources;

    /**
     * get the shared resource between the question in library and in bank
     */
    use BaseQuestionResource;


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
            'share_type' => $this->share_type,

            'fill_in_blanks' => QuestionLibraryFillInBlankResource::collection($this->whenLoaded('FillInBlanks')),
            'fill_texts' => QuestionLibraryFillTextResource::collection($this->whenLoaded('FillTexts')),
            'jumble_sentences' => QuestionLibraryJumbleSentenceResource::collection($this->whenLoaded('JumbleSentences')),
            'matching_left_list' => QuestionLibraryMatchingLeftListResource::collection($this->whenLoaded('MatchingLeftList')),
            'matching_right_list' => QuestionLibraryMatchingRightListResource::collection($this->whenLoaded('MatchingRightList')),
            'multi_choices' => QuestionLibraryMultiChoiceResource::collection($this->whenLoaded('MultiChoices')),
            'ordering' => QuestionLibraryOrderingResource::collection($this->whenLoaded('Ordering')),
            'true_false' => new QuestionLibraryTrueFalseResource($this->whenLoaded('TrueFalse')),

        ]);
    }
}
