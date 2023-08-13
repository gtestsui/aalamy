<?php

namespace App\Modules\User\Http\Resources;

use App\Http\Traits\PaginationResources;
use Illuminate\Http\Resources\Json\JsonResource;
use Modules\ClassModule\Http\Resources\ClassResource;
use Modules\ClassModule\Http\Resources\ClassStudentResource;
use Modules\EducatorStudentRequest\Models\EducatorRosterStudentRequest;
use Modules\Roster\Http\Resources\EducatorRosterStudentRequestResource;
use Modules\StudentAchievement\Http\Resources\StudentAchievementResource;
use Modules\User\Http\Resources\UserResource;
use Modules\User\Traits\CatchUserType;

class StudentResource extends JsonResource
{
    use PaginationResources,CatchUserType;

    public function checkShowParentCode(){
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
          'user_id' => isset($this->user_id)?(int)$this->user_id:$this->user_id,
          'type' => $this->type,
          'parent_email' => $this->parent_email,
          'parent_code' => $this->when($this->checkShowParentCode(),$this->parent_code),
          'is_active' => (bool)$this->is_active,

          'user' => new UserResource($this->whenLoaded('User')),
          'parent_student' => ParentStudentResource::collection($this->whenLoaded('ParentStudents')),
          'class_students' => ClassStudentResource::collection($this->whenLoaded('ClassStudents')),
          'school_student' => new SchoolStudentResource($this->whenLoaded('SchoolStudent')),
          'all_school_student' => SchoolStudentResource::collection($this->whenLoaded('AllSchoolStudent')),
//          'educator_student' => new EducatorStudentResource($this->whenLoaded('EducatorStudent')),
          'educator_students' => EducatorStudentResource::collection($this->whenLoaded('EducatorStudents')),
          'student_achievements' => StudentAchievementResource::collection($this->whenLoaded('Achievements')),
          'educator_roster_student_request' => EducatorRosterStudentRequestResource::collection($this->whenLoaded('EducatorRosterStudentRequests')),


          'basic_information' => $this->whenLoaded('BasicInformation'),
          'family_information' => $this->whenLoaded('FamilyInformation'),
          'other_information' => $this->whenLoaded('OtherInformation'),
          'social_and_personal_information' => $this->whenLoaded('SocialAndPersonalInformation'),

//          'school_student' => SchoolStudentResource::collection($this->whenLoaded('SchoolStudent')),
//          'educator_student' => EducatorStudentResource::collection($this->whenLoaded('EducatorStudent')),
        ];
    }

}
