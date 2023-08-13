<?php

namespace Modules\ClassModule\Http\Resources;

use App\Http\Traits\PaginationResources;
use App\Modules\User\Http\Resources\StudentResource;
use Illuminate\Http\Resources\Json\JsonResource;
use Modules\Level\Http\Resources\LevelResource;
use Modules\User\Http\Resources\UserResource;
use Modules\User\Traits\CatchUserType;

class ClassStudentResource extends JsonResource
{
    use PaginationResources,CatchUserType;

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
            'class_id' => isset($this->class_id)?(int)$this->class_id:$this->class_id,
            'student_id' => isset($this->student_id)?(int)$this->student_id:$this->student_id,
            'is_active' => (bool)$this->is_active,
            'study_year' => $this->study_year,
            'created_at' => refactorCreatedAtFormat($this->created_at),

            'class' => new ClassResource($this->whenLoaded('ClassModel')),
            //Self::$userType from trait CatchUserType
            'student' => new StudentResource($this->whenLoaded('Student'),Self::$userType),
        ];
    }
}
