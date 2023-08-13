<?php

namespace App\Modules\User\Http\Resources;

use App\Http\Traits\PaginationResources;
use App\Modules\SchoolInvitation\Http\Resources\SchoolTeacherRequestResource;
use Illuminate\Http\Resources\Json\JsonResource;
use Modules\User\Http\Resources\UserResource;

class EducatorResource extends JsonResource
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
          'bio' => $this->bio,
          'certificate' => $this->certificate,
          'is_active' => (bool)$this->is_active,
          'deleted' => (bool)$this->deleted,
          'deleted_at' => $this->deleted_at,


            'user' => $this->relationLoaded('User')
              ?new UserResource($this->User)
              :new UserResource($this->whenLoaded('UserEvenItsDeleted')),
          'school_requests' => SchoolTeacherRequestResource::collection($this->whenLoaded('SchoolRequests')),
        ];
    }
}
