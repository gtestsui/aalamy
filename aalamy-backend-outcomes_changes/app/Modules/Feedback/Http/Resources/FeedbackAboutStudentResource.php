<?php

namespace Modules\Feedback\Http\Resources;

use App\Http\Traits\PaginationResources;
use App\Modules\User\Http\Resources\EducatorResource;
use App\Modules\User\Http\Resources\SchoolResource;
use App\Modules\User\Http\Resources\StudentResource;
use App\Modules\User\Http\Resources\TeacherResource;
use Illuminate\Http\Resources\Json\JsonResource;

class FeedbackAboutStudentResource extends JsonResource
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
            'school_id' => isset($this->school_id)?(int)$this->school_id:$this->school_id,
            'teacher_id' => isset($this->teacher_id)?(int)$this->teacher_id:$this->teacher_id,
            'educator_id' => isset($this->educator_id)?(int)$this->educator_id:$this->educator_id,

            'student_id' => isset($this->student_id)?(int)$this->student_id:$this->student_id,
            'text' => $this->text,
            'from_date' => $this->from_date,
            'to_date' => $this->to_date,
            'share_with_parent' => (bool)$this->share_with_parent,
            'deleted' => (bool)$this->deleted,
            'deleted_at' => $this->deleted_at,
            'created_at' => refactorCreatedAtFormat($this->created_at),

            'school' => new SchoolResource($this->whenLoaded('School')),
            'educator' => new EducatorResource($this->whenLoaded('Educator')),
            'teacher' => new TeacherResource($this->whenLoaded('Teacher')),
            'student' => new StudentResource($this->whenLoaded('Student')),

            'images' => $this->whenLoaded('Images'),
            'files' => $this->whenLoaded('Files'),
            'student_attendances' => $this->whenLoaded('StudentAttendances'),
            'student_marks' => $this->whenLoaded('StudentMarks'),

        ];
    }
}
