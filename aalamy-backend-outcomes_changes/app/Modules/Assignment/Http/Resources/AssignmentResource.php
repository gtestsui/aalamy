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

class AssignmentResource extends JsonResource
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

            'level_subject_id' => isset($this->level_subject_id)?(int)$this->level_subject_id:$this->level_subject_id,
            'unit_id' => isset($this->unit_id)?(int)$this->unit_id:$this->unit_id,
            'lesson_id' => isset($this->lesson_id)?(int)$this->lesson_id:$this->lesson_id,

            'is_locked' => (bool)$this->is_locked,
            'is_hidden' => (bool)$this->is_hidden,
            'prevent_request_help' => (bool)$this->prevent_request_help,
            'display_mark' => (bool)$this->display_mark,
            'is_auto_saved' => (bool)$this->is_auto_saved,
            'prevent_moved_between_pages' => (bool)$this->prevent_moved_between_pages,
            'is_shuffling' => (bool)$this->is_shuffling,

            'name' => $this->name,
            'description' => $this->description,
            'pages_count' =>  $this->when(isset($this->pages_count),(int)$this->pages_count),
            'created_at' => refactorCreatedAtFormat($this->created_at),
            'deleted' => (bool)$this->deleted,
            'deleted_at' => $this->deleted_at,
            'school' => new SchoolResource($this->whenLoaded('School')),
            'educator' => new EducatorResource($this->whenLoaded('Educator')),
            'teacher' => new TeacherResource($this->whenLoaded('Teacher')),
            'pages' =>  PageResource::collection($this->whenLoaded('Pages')),
            'level_subject' =>  new LevelSubjectResource($this->whenLoaded('LevelSubject')),
            'unit' =>  new UnitResource($this->whenLoaded('Unit')),
            'lesson' =>  new LessonResource($this->whenLoaded('Lesson')),
            'AssignmentFolder' =>  new AssignmentFolderResource($this->whenLoaded('AssignmentFolder')),

        ];
    }
}
