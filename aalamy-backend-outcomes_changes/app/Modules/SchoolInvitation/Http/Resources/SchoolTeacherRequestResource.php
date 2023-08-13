<?php

namespace App\Modules\SchoolInvitation\Http\Resources;

use App\Http\Traits\PaginationResources;
use App\Modules\User\Http\Resources\EducatorResource;
use App\Modules\User\Http\Resources\SchoolResource;
use App\Modules\User\Http\Resources\StudentResource;
use Illuminate\Http\Resources\Json\JsonResource;
use Modules\Address\Http\Resources\AddressResource;
use Modules\User\Http\Resources\UserResource;

class SchoolTeacherRequestResource extends JsonResource
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
          'educator_id' => isset($this->educator_id)?(int)$this->educator_id:$this->educator_id,
          'school_id' => isset($this->school_id)?(int)$this->school_id:$this->school_id,
          'status' => $this->status,
          'introductory_message' => $this->introductory_message,
          'reject_cause' => $this->reject_cause,
          'from' => $this->from,
          'to' => $this->to,
          'created_at' => refactorCreatedAtFormat($this->created_at),

          'deleted' => (bool)$this->deleted,
          'deleted_at' => (bool)$this->deleted_at,


          'educator' => new EducatorResource($this->whenLoaded('Educator')),
          'school' => new SchoolResource($this->whenLoaded('School')),


        ];
    }
}
