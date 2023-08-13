<?php

namespace App\Modules\User\Http\Resources;

use App\Http\Traits\PaginationResources;
use Illuminate\Http\Resources\Json\JsonResource;
use Modules\User\Http\Resources\UserResource;

class ParentResource extends JsonResource
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
          'is_active' => (bool)$this->is_active,
          'deleted' => (bool)$this->deleted,
          'deleted_at' => $this->deleted_at,


            'user' => new UserResource($this->whenLoaded('User')),
          'parent_student' => ParentStudentResource::collection($this->whenLoaded('ParentStudents')),
        ];
    }
}
