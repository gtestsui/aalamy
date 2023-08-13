<?php

namespace Modules\Outcomes\Http\Resources;

use App\Http\Traits\PaginationResources;
use App\Modules\HelpCenter\Http\Resources\UserGuideResource;
use App\Modules\User\Http\Resources\SchoolResource;
use App\Modules\User\Http\Resources\StudentResource;
use Illuminate\Http\Resources\Json\JsonResource;
use Modules\Level\Http\Resources\LevelResource;
use Modules\User\Http\Resources\UserResource;

class StudentStudyingInformationResource extends JsonResource
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
            'student_id' => isset($this->student_id)?(int)$this->student_id:$this->student_id,
            'school_id' => isset($this->school_id)?(int)$this->school_id:$this->school_id,
            'level_id' => isset($this->level_id)?(int)$this->level_id:$this->level_id,

            'deleted' => (bool)$this->deleted,
            'deleted_at' => $this->deleted_at,
            'created_at' => refactorCreatedAtFormat($this->created_at),
            'student' => new StudentResource($this->whenLoaded('Student')),
            'level' => new LevelResource($this->whenLoaded('Level')),
            'school' => new SchoolResource($this->whenLoaded('School')),
            'marks' => MarkResource::collection($this->whenLoaded('Marks')),

        ];
    }
}
