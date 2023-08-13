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

class GradeBookExternalMarkResource extends JsonResource
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
            'student_id' => isset($this->student_id)?(int)$this->student_id:null,
            'mark' => isset($this->mark)?(int)$this->mark:null,

            'grade_book' => new GradeBookResource($this->whenLoaded('GradeBook')),
            'student' => new StudentResource($this->whenLoaded('Student')),


        ];
    }
}
