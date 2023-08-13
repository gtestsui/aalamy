<?php

namespace Modules\Event\Http\Resources;

use App\Http\Traits\PaginationResources;
use App\Modules\User\Http\Resources\EducatorResource;
use App\Modules\User\Http\Resources\SchoolResource;
use App\Modules\User\Http\Resources\TeacherResource;
use Illuminate\Http\Resources\Json\JsonResource;

class EventResource extends JsonResource
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
            'educator_id' => isset($this->educator_id)?(int)$this->educator_id:$this->educator_id,
            'name' => $this->name,
            'date' => $this->date,
            'deleted' => (bool)$this->deleted,
            'deleted_at' => $this->deleted_at,
//            'school' => isset($this->School)?new SchoolResource($this->whenLoaded('School')):null,
            'school' => new SchoolResource($this->whenLoaded('School')),
            'educator' => new EducatorResource($this->whenLoaded('Educator')),
            'teacher' => new TeacherResource($this->whenLoaded('Teacher')),

        ];
    }
}
