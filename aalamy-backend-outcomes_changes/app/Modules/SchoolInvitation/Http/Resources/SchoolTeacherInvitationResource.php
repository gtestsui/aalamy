<?php

namespace App\Modules\SchoolInvitation\Http\Resources;

use App\Http\Traits\PaginationResources;
use App\Modules\User\Http\Resources\EducatorResource;
use App\Modules\User\Http\Resources\SchoolResource;
use App\Modules\User\Http\Resources\StudentResource;
use Illuminate\Http\Resources\Json\JsonResource;
use Modules\Address\Http\Resources\AddressResource;
use Modules\User\Http\Resources\UserResource;

class SchoolTeacherInvitationResource extends JsonResource
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
//          'type' => $this->type,
//          'link' => $this->link,
          'teacher_email' => $this->teacher_email,
          'deleted' => (bool)$this->deleted,
          'deleted_at' => (bool)$this->deleted_at,

          'educator' => new EducatorResource($this->whenLoaded('Educator')),
          'school' => new SchoolResource($this->whenLoaded('School')),


        ];
    }
}
