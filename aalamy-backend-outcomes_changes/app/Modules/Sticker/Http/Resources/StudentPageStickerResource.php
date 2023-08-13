<?php

namespace Modules\Sticker\Http\Resources;

use App\Http\Traits\PaginationResources;
use App\Modules\User\Http\Resources\EducatorResource;
use App\Modules\User\Http\Resources\SchoolResource;
use App\Modules\User\Http\Resources\TeacherResource;
use Illuminate\Http\Resources\Json\JsonResource;

class StudentPageStickerResource extends JsonResource
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

            'roster_assignment_student_page_id' => isset($this->roster_assignment_student_page_id)?(int)$this->roster_assignment_student_page_id:$this->roster_assignment_student_page_id,
            'sticker_id' => isset($this->sticker_id)?(int)$this->sticker_id:$this->sticker_id,
            'page_id' => isset($this->page_id)?(int)$this->page_id:$this->page_id,
            'student_id' => isset($this->student_id)?(int)$this->student_id:$this->student_id,

            'deleted' => (bool)$this->deleted,
            'deleted_at' => $this->deleted_at,

            'school' => new SchoolResource($this->whenLoaded('School')),
            'educator' => new EducatorResource($this->whenLoaded('Educator')),
            'teacher' => new TeacherResource($this->whenLoaded('Teacher')),

            'sticker' => new StickerResource($this->whenLoaded('Sticker')),

        ];
    }
}
