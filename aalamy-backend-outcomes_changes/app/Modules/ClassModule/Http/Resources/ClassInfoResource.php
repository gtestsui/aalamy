<?php

namespace Modules\ClassModule\Http\Resources;

use App\Http\Traits\PaginationResources;
use App\Modules\User\Http\Resources\EducatorResource;
use App\Modules\User\Http\Resources\SchoolResource;
use App\Modules\User\Http\Resources\TeacherResource;
use Illuminate\Http\Resources\Json\JsonResource;
use Modules\Level\Http\Resources\LevelResource;
use Modules\Level\Http\Resources\LevelSubjectResource;

class ClassInfoResource extends JsonResource
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
        /**
         * we check if the relation its not null
         * because it maybe loaded and the value of it null
         */
        return [
            'id' => $this->id,
            'class_id' => isset($this->class_id)?(int)$this->class_id:$this->class_id,
            'school_id' => isset($this->school_id)?(int)$this->school_id:$this->school_id,
            'level_subject_id' => isset($this->level_subject_id)?(int)$this->level_subject_id:$this->level_subject_id,
            'teacher_id' => isset($this->teacher_id)?(int)$this->teacher_id:$this->teacher_id,
            'educator_id' => isset($this->educator_id)?(int)$this->educator_id:$this->educator_id,
            'class' => new ClassResource($this->whenLoaded('ClassModel')),
//            'school' => isset($this->School)?new SchoolResource($this->School):null,
            'school' => new SchoolResource($this->whenLoaded('School')),
//            'level_subject' => new LevelSubjectResource($this->whenLoaded('LevelSubject')),
            'level_subject' => new LevelSubjectResource($this->whenLoaded('LevelSubject')),
//            'teacher' => isset($this->Teacher)?new TeacherResource($this->Teacher):null,
            'teacher' => new TeacherResource($this->whenLoaded('Teacher')),
//            'educator' => isset($this->Educator)?EducatorResource::collection($this->Educator):null,
            'educator' => new EducatorResource($this->whenLoaded('Educator')),
        ];
    }
}
