<?php

namespace Modules\RosterAssignment\Http\Resources;

use App\Http\Traits\PaginationResources;
use Illuminate\Http\Resources\Json\JsonResource;
use Modules\Assignment\Http\Resources\AssignmentResource;
use Modules\Roster\Http\Resources\RosterResource;

class RosterAssignmentResource extends JsonResource
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
            'assignment_id' => isset($this->assignment_id)?(int)$this->assignment_id:$this->assignment_id,
            'roster_id' => isset($this->roster_id)?(int)$this->roster_id:$this->roster_id,
            'start_date' => $this->start_date,
            'expiration_date' => $this->expiration_date,

            'is_locked' => (bool)$this->is_locked,
            'is_hidden' => (bool)$this->is_hidden,
            'prevent_request_help' => (bool)$this->prevent_request_help,
            'display_mark' => (bool)$this->display_mark,
            'is_auto_saved' => (bool)$this->is_auto_saved,
            'prevent_moved_between_pages' => (bool)$this->prevent_moved_between_pages,
            'is_shuffling' => (bool)$this->is_shuffling,


            'assignment' => new AssignmentResource($this->whenLoaded('Assignment')),
            'roster' => new RosterResource($this->whenLoaded('Roster')),
            'student_actions' => RosterAssignmentStudentActionResource::collection($this->whenLoaded('StudentActions')),
            'roster_assignment_student_attendances' => RosterAssignmentStudentAttendanceResource::collection($this->whenLoaded('RosterAssignmentStudentAttendances')),
            'roster_assignment_pages' => RosterAssignmentPageResource::collection($this->whenLoaded('RosterAssignmentPages')),

        ];
    }
}
