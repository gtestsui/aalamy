<?php

namespace Modules\Mark\Http\Resources;

use App\Http\Traits\PaginationResources;
use Illuminate\Http\Resources\Json\JsonResource;
use Modules\Assignment\Http\Resources\AssignmentResource;
use Modules\Roster\Http\Resources\RosterResource;

class RosterAssignmentMarkResource extends JsonResource
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
            'student_id' => $this->id,
            'user_id' => isset($this->user_id)?(int)$this->user_id:$this->user_id,
            'full_mark' => isset($this->full_mark)?(int)$this->full_mark:0,
            'sticker_mark' => isset($this->sticker_mark)?(int)$this->sticker_mark:0,
            'roster_assignment_mark' => isset($this->roster_assignment_mark)?(int)$this->roster_assignment_mark:0,

            'fname' => $this->relationLoaded('User')?$this->User->fname:null,
            'lname' => $this->relationLoaded('User')?$this->User->lname:null,
            'image' => $this->relationLoaded('User')?$this->User->image:null,

        ];
    }
}
