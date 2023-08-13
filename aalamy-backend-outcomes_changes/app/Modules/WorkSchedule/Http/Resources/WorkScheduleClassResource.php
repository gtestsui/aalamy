<?php

namespace Modules\WorkSchedule\Http\Resources;

use App\Http\Traits\PaginationResources;
use App\Modules\User\Http\Resources\EducatorResource;
use App\Modules\User\Http\Resources\SchoolResource;
use App\Modules\User\Http\Resources\TeacherResource;
use Illuminate\Http\Resources\Json\JsonResource;
use Modules\ClassModule\Http\Resources\ClassResource;
use Modules\RosterAssignment\Http\Resources\RosterAssignmentResource;
use Modules\ClassModule\Http\Resources\ClassInfoResource;
use Modules\User\Traits\CatchUserType;

class WorkScheduleClassResource extends JsonResource
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
            'class_info_id' => isset($this->class_info_id)?(int)$this->class_info_id:$this->class_info_id,
            'week_day_id' => isset($this->week_day_id)?(int)$this->week_day_id:$this->week_day_id,
            'start' => $this->start,
            'end' => $this->end,
            'period_number' => (int)$this->period_number,
            'deleted' => (bool)$this->deleted,
            'deleted_at' => $this->deleted_at,

            'class' => new ClassResource($this->whenLoaded('ClassModel')),
            'class_info' => new ClassInfoResource($this->whenLoaded('ClassInfo')),
            'week_day' => $this->whenLoaded('WeekDay'),
        ];
    }
}
