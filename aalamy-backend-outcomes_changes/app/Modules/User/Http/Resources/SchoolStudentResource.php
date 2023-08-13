<?php

namespace App\Modules\User\Http\Resources;

use App\Http\Traits\PaginationResources;
use Illuminate\Http\Resources\Json\JsonResource;
use Modules\User\Http\Resources\UserResource;

class SchoolStudentResource extends JsonResource
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
          'start_date' => $this->start_date,
          'is_active' => (bool)$this->is_active,
          'student' => new StudentResource($this->whenLoaded('Student'),'school'),
          'school' => new SchoolResource($this->whenLoaded('School')),
        ];
    }
}
