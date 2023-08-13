<?php

namespace Modules\RosterAssignment\Http\Resources;

use App\Http\Traits\PaginationResources;
use App\Modules\User\Http\Resources\StudentResource;
use Illuminate\Http\Resources\Json\JsonResource;

class RosterAssignmentStudentActionResource extends JsonResource
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
            'help_request' => (bool)$this->help_request,
            'check_answer_request' => (bool)$this->check_answer_request,

            'help_requests_count' => (int)$this->help_requests_count,
            'check_answer_requests_count' => (int)$this->check_answer_requests_count,


            'roster_assignment' => new RosterAssignmentResource($this->whenLoaded('RosterAssignment')),
            'student' => new StudentResource($this->whenLoaded('Student')),

        ];
    }
}
