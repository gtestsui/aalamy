<?php

namespace Modules\TeacherPermission\Http\Resources;

use App\Http\Traits\PaginationResources;
use App\Modules\User\Http\Resources\EducatorResource;
use App\Modules\User\Http\Resources\SchoolResource;
use App\Modules\User\Http\Resources\TeacherResource;
use Illuminate\Http\Resources\Json\JsonResource;

class PermissionTeacherResource extends JsonResource
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
            'permission_id' => isset($this->permission_id)?(int)$this->permission_id:$this->permission_id,

            'deleted' => (bool)$this->deleted,
            'deleted_at' => $this->deleted_at,

            'school' => new SchoolResource($this->whenLoaded('School')),
            'teacher' => new TeacherResource($this->whenLoaded('Teacher')),
            'permission' => new PermissionResource($this->whenLoaded('Permission')),

        ];
    }
}
