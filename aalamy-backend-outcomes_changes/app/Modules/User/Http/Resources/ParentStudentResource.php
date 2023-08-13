<?php

namespace App\Modules\User\Http\Resources;

use App\Http\Traits\PaginationResources;
use Illuminate\Http\Resources\Json\JsonResource;
use Modules\User\Http\Resources\UserResource;

class ParentStudentResource extends JsonResource
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
          'parent_id' => isset($this->parent_id)?(int)$this->parent_id:$this->parent_id,
          'student' => new StudentResource($this->whenLoaded('Student'),'parent'),
          'parent' => new ParentResource($this->whenLoaded('Parent')),
        ];
    }
}
