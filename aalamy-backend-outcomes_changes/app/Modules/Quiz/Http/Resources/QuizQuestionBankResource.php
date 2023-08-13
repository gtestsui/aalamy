<?php

namespace Modules\Quiz\Http\Resources;

use App\Http\Traits\PaginationResources;
use Illuminate\Http\Resources\Json\JsonResource;
use Modules\Quiz\Http\Resources\QuestionTypes\QuizQuestionBankFillInBlankResource;
use Modules\Quiz\Http\Resources\QuestionTypes\QuizQuestionBankFillTextResource;
use Modules\Quiz\Http\Resources\QuestionTypes\QuizQuestionBankJumbleSentenceResource;
use Modules\Quiz\Http\Resources\QuestionTypes\QuizQuestionBankMatchingLeftListResource;
use Modules\Quiz\Http\Resources\QuestionTypes\QuizQuestionBankMatchingRightListResource;
use Modules\Quiz\Http\Resources\QuestionTypes\QuizQuestionBankMultiChoiceResource;
use Modules\Quiz\Http\Resources\QuestionTypes\QuizQuestionBankOrderingResource;
use Modules\Quiz\Http\Resources\QuestionTypes\QuizQuestionBankTrueFalseResource;

class QuizQuestionBankResource extends JsonResource
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
            'question' => $this->question,
            'question_type' => $this->question_type,


            'quiz' => new QuizResource($this->whenLoaded('Quiz')),


            'fill_in_blanks' => QuizQuestionBankFillInBlankResource::collection($this->whenLoaded('FillInBlanks')),
            'fill_texts' => QuizQuestionBankFillTextResource::collection($this->whenLoaded('FillTexts')),
            'jumble_sentences' => QuizQuestionBankJumbleSentenceResource::collection($this->whenLoaded('JumbleSentences')),
            'matching_left_list' => QuizQuestionBankMatchingLeftListResource::collection($this->whenLoaded('MatchingLeftList')),
            'matching_right_list' => QuizQuestionBankMatchingRightListResource::collection($this->whenLoaded('MatchingRightList')),
            'multi_choices' => QuizQuestionBankMultiChoiceResource::collection($this->whenLoaded('MultiChoices')),
            'ordering' => QuizQuestionBankOrderingResource::collection($this->whenLoaded('Ordering')),
            'true_false' => new QuizQuestionBankTrueFalseResource($this->whenLoaded('TrueFalse')),

        ];
    }
}
