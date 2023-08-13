<?php

namespace Modules\Quiz\Http\Resources\QuestionStudentAnswerTypes;

use App\Http\Traits\PaginationResources;
use Illuminate\Http\Resources\Json\JsonResource;
use Modules\QuestionBank\Traits\BaseQuestionTypesResources\BaseMatchingRightListResource;
use Modules\Quiz\Http\Resources\QuizQuestionBankResource;

class QuizQuestionStudentAnswerMatchingResource extends JsonResource
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
            'left_list_id' => (int)$this->left_list_id,
            'right_list_id' => (int)$this->right_list_id,

        ];
    }
}
