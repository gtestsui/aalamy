<?php

namespace Modules\Quiz\Http\Resources\QuestionStudentAnswerTypes;

use App\Http\Traits\PaginationResources;
use Illuminate\Http\Resources\Json\JsonResource;
use Modules\QuestionBank\Traits\BaseQuestionTypesResources\BaseFillInBlankResource;
use Modules\Quiz\Http\Resources\QuizQuestionBankResource;

class QuizQuestionStudentAnswerFillInBlankResource extends JsonResource
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
            'id' => (int)$this->id,
            'quiz_question_student_answer_id' => (int)$this->quiz_question_student_answer_id,
            'word' => $this->word,
            'order' => (int)$this->order,


        ];
    }
}
