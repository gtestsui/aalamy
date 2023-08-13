<?php

namespace Modules\Quiz\Http\Resources;

use App\Http\Traits\PaginationResources;
use App\Modules\User\Http\Resources\EducatorResource;
use App\Modules\User\Http\Resources\SchoolResource;
use App\Modules\User\Http\Resources\TeacherResource;
use Illuminate\Http\Resources\Json\JsonResource;
use Modules\QuestionBank\Http\Resources\QuestionBank\QuestionBankResource;
use Modules\Quiz\Traits\CatchIsQuizOwner;
use Modules\Quiz\Traits\ResourceSharedData\QuizQuestionResourceSharedDataTrait;

class QuizQuestionResource extends JsonResource
{
    use PaginationResources,CatchIsQuizOwner;

    use QuizQuestionResourceSharedDataTrait;



    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {

        return array_merge($this->getSharedData(),[

//            'id' => $this->id,
//            'quiz_id' => isset($this->quiz_id)?(int)$this->quiz_id:$this->quiz_id,
//            'question_id' => isset($this->question_id)?(int)$this->question_id:$this->question_id,
//            'mark' => (int)$this->mark,
//
//
//            'deleted' => (bool)$this->deleted,
//            'deleted_at' => $this->deleted_at,
//
//
//            'quiz' => new QuizResource($this->whenLoaded('Quiz')),
            'question' => isset(self::$isOwner)&&self::$isOwner===true
                ? new QuestionBankResource($this->whenLoaded('QuestionBank'))
                : new QuizQuestionBankResource($this->whenLoaded('QuestionBank')),

            'student_answer' => QuizQuestionStudentAnswerResource::collection($this->whenLoaded('QuizQuestionStudentAnswers')),

        ]);
    }
}
