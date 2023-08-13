<?php

namespace Modules\Quiz\Traits\ResourceSharedData;


use Modules\Quiz\Http\Resources\QuestionStudentAnswerTypes\QuizQuestionStudentAnswerFillInBlankResource;
use Modules\Quiz\Http\Resources\QuestionStudentAnswerTypes\QuizQuestionStudentAnswerFillTextResource;
use Modules\Quiz\Http\Resources\QuestionStudentAnswerTypes\QuizQuestionStudentAnswerJumbleSentenceResource;
use Modules\Quiz\Http\Resources\QuestionStudentAnswerTypes\QuizQuestionStudentAnswerMatchingResource;
use Modules\Quiz\Http\Resources\QuestionStudentAnswerTypes\QuizQuestionStudentAnswerMultiChoiceResource;
use Modules\Quiz\Http\Resources\QuestionStudentAnswerTypes\QuizQuestionStudentAnswerOrderingResource;
use Modules\Quiz\Http\Resources\QuestionStudentAnswerTypes\QuizQuestionStudentAnswerTrueFalseResource;
use Modules\Quiz\Http\Resources\QuizResource;

trait QuizQuestionStudentAnswerResourceSharedDataTrait
{

    public function getSharedData(){
        return [

            'id' => $this->id,
            'quiz_question_id' => (int)$this->quiz_question_id,
            'student_id' => (int)$this->student_id,
            'quiz' => new QuizResource($this->whenLoaded('Quiz')),


            'fill_in_blanks' => QuizQuestionStudentAnswerFillInBlankResource::collection($this->whenLoaded('FillInBlankAnswers')),
            'fill_text' => new QuizQuestionStudentAnswerFillTextResource($this->whenLoaded('FillTextAnswer')),
            'jumble_sentences' => QuizQuestionStudentAnswerJumbleSentenceResource::collection($this->whenLoaded('JumbleSentenceAnswers')),
            'matching' => QuizQuestionStudentAnswerMatchingResource::collection($this->whenLoaded('MatchingAnswers')),
            'multi_choices' => new QuizQuestionStudentAnswerMultiChoiceResource($this->whenLoaded('MultiChoiceAnswer')),
            'ordering' => QuizQuestionStudentAnswerOrderingResource::collection($this->whenLoaded('OrderingAnswers')),
            'true_false' => new QuizQuestionStudentAnswerTrueFalseResource($this->whenLoaded('TrueFalseAnswer')),

        ];
    }

}
