<?php

namespace Modules\Roster\Http\Resources;

use App\Http\Traits\PaginationResources;
use App\Modules\User\Http\Resources\EducatorResource;
use App\Modules\User\Http\Resources\SchoolResource;
use App\Modules\User\Http\Resources\TeacherResource;
use Illuminate\Http\Resources\Json\JsonResource;
use Modules\RosterAssignment\Http\Resources\RosterAssignmentResource;
use Modules\ClassModule\Http\Resources\ClassInfoResource;
use Modules\User\Traits\CatchUserType;

class RosterResource extends JsonResource
{
    use PaginationResources,CatchUserType;




    public function checkShowCode(){
        if(is_null(Self::$userType) )
            return false;
        if(Self::$userType=='educator'||Self::$userType=='school'||Self::$userType=='superAdmin')
            return true;
        return false;
    }

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
            'class_info_id' => isset($this->class_info_id)?(int)$this->class_info_id:$this->class_info_id,
            'name' => $this->name,
            'description' => $this->description,
            'color' => $this->color,
            'code' => $this->when($this->checkShowCode(),$this->code),
            'is_closed' => (bool)$this->is_closed,
            'deleted' => (bool)$this->deleted,
            'deleted_at' => $this->deleted_at,

            'class_info' => new ClassInfoResource($this->whenLoaded('ClassInfo')),
            'created_by_teacher' => new TeacherResource($this->whenLoaded('CreatedByTeacher')),
            'created_by_school' => new SchoolResource($this->whenLoaded('CreatedBySchool')),
            'created_by_educator' => new EducatorResource($this->whenLoaded('CreatedByEducator')),
            'roster_assignments' => RosterAssignmentResource::collection($this->whenLoaded('RosterAssignments')),
            //if we have put the name as previous(available_roster_assignments,roster_assignments)
            //so always if no AvailableRosterAssignments loaded will return null
            'available_roster_assignments' => RosterAssignmentResource::collection($this->whenLoaded('AvailableRosterAssignments')),
        ];
    }
}
