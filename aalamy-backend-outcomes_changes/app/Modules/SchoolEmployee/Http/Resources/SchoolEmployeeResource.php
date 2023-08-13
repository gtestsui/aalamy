<?php

namespace Modules\SchoolEmployee\Http\Resources;

use App\Http\Traits\PaginationResources;
use App\Modules\User\Http\Resources\TeacherResource;
use Illuminate\Http\Resources\Json\JsonResource;
use Modules\Level\Http\Resources\SubjectResource;

class SchoolEmployeeResource extends JsonResource
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
            'school_id' => (int)$this->school_id,
            'teacher_id' => isset($this->teacher_id)?(int)$this->teacher_id:$this->teacher_id,
            'fname' => $this->fname,
            'lname' => $this->lname,
            'father_name' => $this->father_name,
            'mother_name' => $this->mother_name,
            'grandfather_name' => $this->grandfather_name,
            'gender' => $this->gender,
            'place_of_birth' => $this->place_of_birth,
            'date_of_birth' => refactorCreatedAtFormat($this->date_of_birth,false),
            'original_state' => $this->original_state,
            'place_of_registration' => $this->place_of_registration,
            'number_of_registration' => $this->number_of_registration,
            'nationality' => $this->nationality,
            'identifier_number' => $this->identifier_number,
            'address' => $this->address,
            'marriage_state' => $this->marriage_state,
            'job_info' => $this->job_info,
            'phone_number' => $this->phone_number,
            'phone_code' => $this->phone_code,
            'phone_iso_code' => $this->phone_iso_code,

            'experience' => $this->experience,
            'computer_skills' => $this->computer_skills,
            'added_manually_by_school' => $this->added_manually_by_school,
            'type' => $this->type,

            'certificates' => SchoolEmployeeCertificateResource::collection(
                $this->whenLoaded('Certificates')
            ),

            'teacher' => new TeacherResource(
                $this->whenLoaded('Teacher')
            )

        ];
    }
}
