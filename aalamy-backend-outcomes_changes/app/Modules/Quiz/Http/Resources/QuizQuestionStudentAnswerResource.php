<?php

namespace Modules\Quiz\Http\Resources;

use App\Http\Traits\PaginationResources;
use Illuminate\Http\Resources\Json\JsonResource;
use Modules\Quiz\Http\Resources\QuestionStudentAnswerTypes\QuizQuestionStudentAnswerFillInBlankResource;
use Modules\Quiz\Http\Resources\QuestionStudentAnswerTypes\QuizQuestionStudentAnswerFillTextResource;
use Modules\Quiz\Http\Resources\QuestionStudentAnswerTypes\QuizQuestionStudentAnswerJumbleSentenceResource;
use Modules\Quiz\Http\Resources\QuestionStudentAnswerTypes\QuizQuestionStudentAnswerMatchingResource;
use Modules\Quiz\Http\Resources\QuestionStudentAnswerTypes\QuizQuestionStudentAnswerMultiChoiceResource;
use Modules\Quiz\Http\Resources\QuestionStudentAnswerTypes\QuizQuestionStudentAnswerOrderingResource;
use Modules\Quiz\Http\Resources\QuestionStudentAnswerTypes\QuizQuestionStudentAnswerTrueFalseResource;
use Modules\Quiz\Traits\ResourceSharedData\QuizQuestionStudentAnswerResourceSharedDataTrait;

class QuizQuestionStudentAnswerResource extends JsonResource
{
    use PaginationResources;

    use QuizQuestionStudentAnswerResourceSharedDataTrait;



    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {

        return  $this->getSharedData();
//        return [
//
//            'id' => $this->id,
//            'quiz_question_id' => (int)$this->quiz_question_id,
//            'student_id' => (int)$this->student_id,
//
//
//            'quiz' => new QuizResource($this->whenLoaded('Quiz')),
//
//
//            'fill_in_blanks' => QuizQuestionStudentAnswerFillInBlankResource::collection($this->whenLoaded('FillInBlankAnswers')),
//            'fill_text' => new QuizQuestionStudentAnswerFillTextResource($this->whenLoaded('FillTextAnswer')),
//            'jumble_sentences' => QuizQuestionStudentAnswerJumbleSentenceResource::collection($this->whenLoaded('JumbleSentenceAnswers')),
//            'matching' => QuizQuestionStudentAnswerMatchingResource::collection($this->whenLoaded('MatchingAnswers')),
//            'multi_choices' => new QuizQuestionStudentAnswerMultiChoiceResource($this->whenLoaded('MultiChoiceAnswer')),
//            'ordering' => QuizQuestionStudentAnswerOrderingResource::collection($this->whenLoaded('OrderingAnswers')),
//            'true_false' => new QuizQuestionStudentAnswerTrueFalseResource($this->whenLoaded('TrueFalseAnswer')),
//
//        ];
    }
}
