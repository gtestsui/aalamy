<?php

namespace Modules\Assignment\Http\Resources;

use App\Http\Traits\PaginationResources;
use App\Modules\User\Http\Resources\EducatorResource;
use App\Modules\User\Http\Resources\SchoolResource;
use App\Modules\User\Http\Resources\TeacherResource;
use Illuminate\Http\Resources\Json\JsonResource;
use Modules\Level\Http\Resources\LessonResource;
use Modules\Level\Http\Resources\LevelSubjectResource;
use Modules\Level\Http\Resources\UnitResource;
use Modules\Level\Models\LevelSubject;

class AssignmentFolderResource extends JsonResource
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
            'parent_id' => isset($this->parent_id)?(int)$this->parent_id:$this->parent_id,


            'name' => $this->name,

            'created_at' => refactorCreatedAtFormat($this->created_at),
            'deleted' => (bool)$this->deleted,
            'deleted_at' => $this->deleted_at,

            'school' => new SchoolResource($this->whenLoaded('School')),
            'educator' => new EducatorResource($this->whenLoaded('Educator')),
            'teacher' => new TeacherResource($this->whenLoaded('Teacher')),
            'parent' => new AssignmentFolderResource($this->whenLoaded('Parent')),

        ];
    }
}
