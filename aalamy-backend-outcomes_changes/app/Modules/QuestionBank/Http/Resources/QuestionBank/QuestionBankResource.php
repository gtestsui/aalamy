<?php

namespace Modules\QuestionBank\Http\Resources\QuestionBank;

use App\Http\Traits\PaginationResources;
use App\Modules\User\Http\Resources\EducatorResource;
use App\Modules\User\Http\Resources\SchoolResource;
use App\Modules\User\Http\Resources\TeacherResource;
use Illuminate\Http\Resources\Json\JsonResource;
use Modules\Level\Http\Resources\LessonResource;
use Modules\Level\Http\Resources\LevelSubjectResource;
use Modules\Level\Http\Resources\UnitResource;
use Modules\QuestionBank\Traits\BaseQuestionTypesResources\BaseQuestionResource;

class QuestionBankResource extends JsonResource
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

            'shared_with_library' => (bool)$this->shared_with_library,

            'fill_in_blanks' => QuestionBankFillInBlankResource::collection($this->whenLoaded('FillInBlanks')),
            'fill_texts' => QuestionBankFillTextResource::collection($this->whenLoaded('FillTexts')),
            'jumble_sentences' => QuestionBankJumbleSentenceResource::collection($this->whenLoaded('JumbleSentences')),
            'matching_left_list' => QuestionBankMatchingLeftListResource::collection($this->whenLoaded('MatchingLeftList')),
            'matching_right_list' => QuestionBankMatchingRightListResource::collection($this->whenLoaded('MatchingRightList')),
            'multi_choices' => QuestionBankMultiChoiceResource::collection($this->whenLoaded('MultiChoices')),
            'ordering' => QuestionBankOrderingResource::collection($this->whenLoaded('Ordering')),
            'true_false' => new QuestionBankTrueFalseResource($this->whenLoaded('TrueFalse')),
        ]);
    }
}
