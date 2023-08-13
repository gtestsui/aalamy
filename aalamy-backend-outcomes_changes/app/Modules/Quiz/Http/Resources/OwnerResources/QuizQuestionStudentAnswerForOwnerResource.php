<?php

namespace Modules\Quiz\Http\Resources\OwnerResources;

use App\Http\Traits\PaginationResources;
use Illuminate\Http\Resources\Json\JsonResource;
use Modules\Quiz\Traits\ResourceSharedData\QuizQuestionStudentAnswerResourceSharedDataTrait;

class QuizQuestionStudentAnswerForOwnerResource extends JsonResource
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
        return array_merge($this->getSharedData(),[
            'mark' => (float)$this->mark,
            'answer_status' => (bool)$this->answer_status,

        ]);
    }
}
