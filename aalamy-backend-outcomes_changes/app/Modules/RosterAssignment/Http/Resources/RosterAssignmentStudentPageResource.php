<?php

namespace Modules\RosterAssignment\Http\Resources;

use App\Http\Traits\PaginationResources;
use Illuminate\Http\Resources\Json\JsonResource;
use Modules\Assignment\Http\Resources\AssignmentResource;
use Modules\Roster\Http\Resources\RosterResource;

class RosterAssignmentStudentPageResource extends JsonResource
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
            'roster_assignment_page_id' => isset($this->roster_assignment_page_id)?(int)$this->roster_assignment_page_id:$this->roster_assignment_page_id,
            'student_id' => isset($this->student_id)?(int)$this->student_id:$this->student_id,

            'is_hidden' => (bool)$this->is_hidden,
            'is_locked' => (bool)$this->is_locked,

            'created_at' => refactorCreatedAtFormat($this->created_at),


        ];
    }
}
