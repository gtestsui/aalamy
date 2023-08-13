<?php

namespace Modules\Assignment\Http\Resources;

use App\Http\Traits\PaginationResources;
use App\Modules\User\Http\Resources\EducatorResource;
use App\Modules\User\Http\Resources\SchoolResource;
use App\Modules\User\Http\Resources\TeacherResource;
use Illuminate\Http\Resources\Json\JsonResource;
use Modules\RosterAssignment\Http\Resources\RosterAssignmentPageResource;

class PageResource extends JsonResource
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
            'page' => $this->page,
            'is_empty' => (bool)$this->is_empty,
            'is_hidden' => (bool)$this->is_hidden,
            'is_locked' => (bool)$this->is_locked,
            'timer' => $this->timer,
            'order' => (int)$this->order,
            'created_at' => refactorCreatedAtFormat($this->created_at),

            'assignment' => new AssignmentResource($this->whenLoaded('Assignment')),
            'roster_assignment_pages' => RosterAssignmentPageResource::collection($this->whenLoaded('RosterAssignmentPages')),

        ];
    }
}
