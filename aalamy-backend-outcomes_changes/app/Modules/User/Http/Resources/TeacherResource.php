<?php

namespace App\Modules\User\Http\Resources;

use App\Http\Traits\PaginationResources;
use Illuminate\Http\Resources\Json\JsonResource;
use Modules\ClassModule\Http\Resources\ClassInfoResource;
use Modules\User\Http\Resources\UserResource;

class TeacherResource extends JsonResource
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
          'user_id' => isset($this->user_id)?(int)$this->user_id:$this->user_id,
          'school_id' => isset($this->school_id)?(int)$this->school_id:$this->school_id,
          'bio' => $this->bio,
          'is_active' => (bool)$this->is_active,
          'class_infos' => ClassInfoResource::collection($this->whenLoaded('ClassInfos')),
          'created_at' => refactorCreatedAtFormat($this->created_at),
          'deleted' => (bool)$this->deleted,
          'deleted_at' => $this->deleted_at,



          'user' => $this->relationLoaded('User')
             ?new UserResource($this->User)
             :new UserResource($this->whenLoaded('UserEvenItsDeleted')),

          'school' => new SchoolResource($this->whenLoaded('School')),
        ];
    }
}
