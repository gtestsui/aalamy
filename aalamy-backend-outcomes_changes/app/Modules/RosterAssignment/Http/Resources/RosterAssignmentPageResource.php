<?php

namespace Modules\RosterAssignment\Http\Resources;

use App\Http\Traits\PaginationResources;
use Illuminate\Http\Resources\Json\JsonResource;
use Modules\Assignment\Http\Resources\AssignmentResource;
use Modules\Assignment\Http\Resources\PageResource;
use Modules\Roster\Http\Resources\RosterResource;

class RosterAssignmentPageResource extends JsonResource
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
            'page_id' => isset($this->page_id)?(int)$this->page_id:$this->page_id,

            'is_hidden' => (bool)$this->is_hidden,
            'is_locked' => (bool)$this->is_locked,

            'created_at' => refactorCreatedAtFormat($this->created_at),
            'roster_assignment_student_pages' => RosterAssignmentStudentPageResource::collection($this->whenLoaded('RosterAssignmentStudentPages')),
            'page' => new PageResource($this->whenLoaded('Page')),


        ];
    }
}
