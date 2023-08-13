<?php

namespace Modules\Mark\Http\Resources;

use App\Http\Traits\PaginationResources;
use App\Modules\User\Http\Resources\SchoolResource;
use App\Modules\User\Http\Resources\StudentResource;
use Illuminate\Http\Resources\Json\JsonResource;
use Modules\Assignment\Http\Resources\AssignmentResource;
use Modules\Quiz\Http\Resources\QuizResource;
use Modules\Roster\Http\Resources\RosterResource;
use Modules\RosterAssignment\Http\Resources\RosterAssignmentResource;

class GradeBookQuizResource extends JsonResource
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
            'grade_book_id' => isset($this->grade_book_id)?(int)$this->grade_book_id:null,
            'quiz_id' => isset($this->quiz_id)?(int)$this->quiz_id:null,
            'weight' => isset($this->weight)?(int)$this->weight:null,

            'grade_book' => new GradeBookResource($this->whenLoaded('GradeBook')),
            'quiz' => new QuizResource($this->whenLoaded('Quiz')),


        ];
    }
}
