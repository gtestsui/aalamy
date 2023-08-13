<?php

namespace App\Modules\User\Http\Resources;

use App\Http\Traits\PaginationResources;
use Illuminate\Http\Resources\Json\JsonResource;
use Modules\User\Http\Resources\UserResource;

class EducatorStudentResource extends JsonResource
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
          'educator_id' => isset($this->educator_id)?(int)$this->educator_id:$this->educator_id,
          'start_date' => $this->start_date,
          'student' => new StudentResource($this->whenLoaded('Student'),'educator'),
          'educator' => new EducatorResource($this->whenLoaded('Educator')),
        ];
    }
}
