<?php

namespace Modules\TeacherPermission\Http\Resources;

use App\Http\Traits\PaginationResources;
use App\Modules\User\Http\Resources\EducatorResource;
use App\Modules\User\Http\Resources\SchoolResource;
use App\Modules\User\Http\Resources\TeacherResource;
use Illuminate\Http\Resources\Json\JsonResource;

class PermissionResource extends JsonResource
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
            'name' => $this->name,
            'num' => isset($this->num)?(int)$this->num:$this->num,

            'deleted' => (bool)$this->deleted,
            'deleted_at' => $this->deleted_at,


            'permission_teacher' => PermissionTeacherResource::collection($this->whenLoaded('PermissionTeachers')),

        ];
    }
}
