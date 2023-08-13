<?php

namespace Modules\Meeting\Traits\ResourceSharedData;


use App\Modules\User\Http\Resources\EducatorResource;
use App\Modules\User\Http\Resources\ParentResource;
use App\Modules\User\Http\Resources\SchoolResource;
use App\Modules\User\Http\Resources\StudentResource;
use App\Modules\User\Http\Resources\TeacherResource;

trait MeetingTargetUserResourceSharedDataTrait
{

    public function getSharedData(){
        return [
            'id' => $this->id,
            'meeting_id' => isset($this->meeting_id)?(int)$this->meeting_id:$this->meeting_id,
            'parent_id' => isset($this->parent_id)?(int)$this->parent_id:$this->parent_id,
            'teacher_id' => isset($this->teacher_id)?(int)$this->teacher_id:$this->teacher_id,
            'student_id' => isset($this->student_id)?(int)$this->student_id:$this->student_id,
            'attendee_status' => (bool)$this->attendee_status,
            'note' => $this->note,
            'updated_at' => $this->updated_at,
            'deleted' => (bool)$this->deleted,
            'deleted_at' => $this->deleted_at,

            'parent' => new ParentResource($this->whenLoaded('Parent')),
            'student' => new StudentResource($this->whenLoaded('Student')),
            'teacher' => new TeacherResource($this->whenLoaded('Teacher')),
        ];
    }

}
