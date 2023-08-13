<?php

namespace App\Modules\User\Http\Resources;

use App\Http\Traits\PaginationResources;
use App\Modules\SchoolInvitation\Http\Resources\SchoolStudentRequestResource;
use App\Modules\SchoolInvitation\Http\Resources\SchoolTeacherRequestResource;
use Illuminate\Http\Resources\Json\JsonResource;
use Modules\Address\Http\Resources\AddressResource;
use Modules\ClassModule\Http\Resources\ClassStudentResource;
use Modules\User\Http\Resources\UserResource;

class SchoolResource extends JsonResource
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
          'user' => new UserResource($this->whenLoaded('User')),
          'bio' => $this->bio,
          'school_name' => $this->school_name,
          'school_image' => $this->school_image,
          'is_active' => (bool)$this->is_active,
          'address_id' => isset($this->address_id)?(int)$this->address_id:$this->address_id,
          'created_at'=> refactorCreatedAtFormat($this->created_at),
          'allow_student_request' => (bool)$this->allow_student_request,
          'allow_teacher_request' => (bool)$this->allow_teacher_request,
          'deleted' => (bool)$this->deleted,
          'deleted_at' => $this->deleted_at,

          'address' => new AddressResource($this->whenLoaded('Address')),
          'student_requests' => SchoolStudentRequestResource::collection($this->whenLoaded('StudentRequests')),
          'teachers' => TeacherResource::collection($this->whenLoaded('Teachers')),
          'teacher_requests' => SchoolTeacherRequestResource::collection($this->whenLoaded('TeacherRequests')),
          'school_students' => SchoolStudentResource::collection($this->whenLoaded('SchoolStudents')),
          'class_students' => ClassStudentResource::collection($this->whenLoaded('ClassStudents')),

        ];
    }
}
