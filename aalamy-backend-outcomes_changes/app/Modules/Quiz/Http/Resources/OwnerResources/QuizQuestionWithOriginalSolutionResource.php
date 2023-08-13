<?php

namespace Modules\Quiz\Http\Resources\OwnerResources;

use App\Http\Traits\PaginationResources;
use App\Modules\User\Http\Resources\EducatorResource;
use App\Modules\User\Http\Resources\SchoolResource;
use App\Modules\User\Http\Resources\TeacherResource;
use Illuminate\Http\Resources\Json\JsonResource;
use Modules\QuestionBank\Http\Resources\QuestionBank\QuestionBankResource;
use Modules\Quiz\Traits\CatchIsQuizOwner;
use Modules\Quiz\Traits\ResourceSharedData\QuizQuestionResourceSharedDataTrait;

class QuizQuestionWithOriginalSolutionResource extends JsonResource
{
    use PaginationResources/*,CatchIsQuizOwner*/;

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
            'question' => new QuestionBankResource($this->whenLoaded('QuestionBank')),

//            'student_answer' => QuizQuestionStudentAnswerResource::collection($this->whenLoaded('QuizQuestionStudentAnswers')),

        ]);
    }
}
