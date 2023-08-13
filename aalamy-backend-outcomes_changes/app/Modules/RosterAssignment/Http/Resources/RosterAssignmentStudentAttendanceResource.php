<?php

namespace Modules\RosterAssignment\Http\Resources;

use App\Http\Traits\PaginationResources;
use App\Modules\User\Http\Resources\StudentResource;
use Illuminate\Http\Resources\Json\JsonResource;

class RosterAssignmentStudentAttendanceResource extends JsonResource
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
            'roster_assignment_id' => isset($this->roster_assignment_id)?(int)$this->roster_assignment_id:$this->roster_assignment_id,
            'student_id' => isset($this->student_id)?(int)$this->student_id:$this->student_id,

            'attendee_status' => (bool)$this->attendee_status,
            'note' => $this->note,

            'roster_assignment' => new RosterAssignmentResource($this->whenLoaded('RosterAssignment')),
            'student' => new StudentResource($this->whenLoaded('Student')),

        ];
    }
}
