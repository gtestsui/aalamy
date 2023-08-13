<?php

namespace Modules\Quiz\Http\Resources;

use App\Http\Traits\PaginationResources;
use App\Modules\User\Http\Resources\EducatorResource;
use App\Modules\User\Http\Resources\SchoolResource;
use App\Modules\User\Http\Resources\TeacherResource;
use Illuminate\Http\Resources\Json\JsonResource;
use Modules\Quiz\Traits\CatchIsQuizOwner;
use Modules\Quiz\Traits\ResourceSharedData\QuizResourceSharedDataTrait;

class QuizResource extends JsonResource
{
    use PaginationResources,CatchIsQuizOwner;

    use QuizResourceSharedDataTrait;



    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return array_merge($this->getSharedData(),[

            'questions' => QuizQuestionResource::CustomCollection($this->whenLoaded('Questions'),self::$isOwner),
            'quiz_students_count' => $this->when(isset($this->quiz_students_count),(int)$this->quiz_students_count),
        ]);
    }
}
