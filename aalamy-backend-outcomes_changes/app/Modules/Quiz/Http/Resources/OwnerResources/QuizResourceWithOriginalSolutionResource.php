<?php

namespace Modules\Quiz\Http\Resources\OwnerResources;

use App\Http\Traits\PaginationResources;
use App\Modules\User\Http\Resources\EducatorResource;
use App\Modules\User\Http\Resources\SchoolResource;
use App\Modules\User\Http\Resources\TeacherResource;
use Illuminate\Http\Resources\Json\JsonResource;
use Modules\Quiz\Traits\CatchIsQuizOwner;
use Modules\Quiz\Traits\ResourceSharedData\QuizResourceSharedDataTrait;

/**
 * return the question with questions answers (from question bank)
 */
class QuizResourceWithOriginalSolutionResource extends JsonResource
{
    use PaginationResources/*,CatchIsQuizOwner*/;
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

            'questions' => QuizQuestionWithOriginalSolutionResource::collection($this->whenLoaded('Questions')),

        ]);
    }
}
