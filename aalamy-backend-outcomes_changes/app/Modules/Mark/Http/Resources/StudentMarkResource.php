<?php

namespace Modules\Mark\Http\Resources;

use App\Http\Traits\PaginationResources;
use Illuminate\Http\Resources\Json\JsonResource;
use Modules\Assignment\Http\Resources\AssignmentResource;
use Modules\Roster\Http\Resources\RosterResource;

class StudentMarkResource extends JsonResource
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
            'assignment_name' =>  $this->relationLoaded('Assignment')?$this->Assignment->name:null,


            'roster_assignment_mark' => $this->roster_assignment_mark,
            'full_mark' => $this->full_mark,
            'sticker_mark' => $this->sticker_mark,



//            'assignment' => new AssignmentResource($this->whenLoaded('Assignment')),
            'roster' => new RosterResource($this->whenLoaded('Roster')),

        ];
    }
}
