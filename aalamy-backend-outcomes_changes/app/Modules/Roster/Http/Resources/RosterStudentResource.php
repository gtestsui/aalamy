<?php

namespace Modules\Roster\Http\Resources;

use App\Http\Traits\PaginationResources;
use Illuminate\Http\Resources\Json\JsonResource;
use Modules\ClassModule\Http\Resources\ClassInfoResource;
use Modules\ClassModule\Http\Resources\ClassStudentResource;
use Modules\User\Traits\CatchUserType;

class RosterStudentResource extends JsonResource
{
    use PaginationResources,CatchUserType;




    /*public function checkShowCode(){
        if(is_null(Self::$userType) )
            return false;
        if(Self::$userType=='educator'||Self::$userType=='school')
            return true;
        return false;
    }*/

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
            'roster_id' => isset($this->roster_id)?(int)$this->roster_id:$this->roster_id,
            'class_student_id' => isset($this->class_student_id)?(int)$this->class_student_id:$this->class_student_id,
            'roster' => new RosterResource($this->whenLoaded('Roster')),
            'class_student' => new ClassStudentResource($this->whenLoaded('ClassStudent')),
        ];
    }
}
