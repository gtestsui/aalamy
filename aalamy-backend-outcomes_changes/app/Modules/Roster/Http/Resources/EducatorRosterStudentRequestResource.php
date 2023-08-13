<?php

namespace Modules\Roster\Http\Resources;

use App\Http\Traits\PaginationResources;
use App\Modules\User\Http\Resources\EducatorResource;
use App\Modules\User\Http\Resources\StudentResource;
use Illuminate\Http\Resources\Json\JsonResource;
use Modules\ClassModule\Http\Resources\ClassInfoResource;
use Modules\User\Traits\CatchUserType;

class EducatorRosterStudentRequestResource extends JsonResource
{
    use PaginationResources,CatchUserType;




//    public function checkShowCode(){
//        if(is_null(Self::$userType) )
//            return false;
//        if(Self::$userType=='educator'||Self::$userType=='school')
//            return true;
//        return false;
//    }

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
            'educator_id' => isset($this->educator_id)?(int)$this->educator_id:$this->educator_id,
            'student_id' => isset($this->student_id)?(int)$this->student_id:$this->student_id,
            'roster_id' => isset($this->roster_id)?(int)$this->roster_id:$this->roster_id,
            'status' => $this->status,
            'introductory_message' => $this->introductory_message,
            'reject_cause' => $this->reject_cause,
            'from' => $this->from,
            'to' => $this->to,
            'created_at' => refactorCreatedAtFormat($this->created_at),
            'educator' => new EducatorResource($this->whenLoaded('Educator')),
            'Student' => new StudentResource($this->whenLoaded('Student')),
            'roster' => new RosterResource($this->whenLoaded('Roster')),
        ];
    }
}
