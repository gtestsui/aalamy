<?php

namespace Modules\Notification\Http\Resources;

use App\Http\Traits\PaginationResources;
use App\Modules\User\Http\Resources\EducatorResource;
use App\Modules\User\Http\Resources\SchoolResource;
use App\Modules\User\Http\Resources\TeacherResource;
use Illuminate\Http\Resources\Json\JsonResource;
use Modules\User\Http\Resources\UserResource;
use Modules\User\Models\Educator;

class ManualNotificationResource extends JsonResource
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
//          'user_id' => isset($this->user_id)?(int)$this->user_id:$this->user_id,
          'school_id' => isset($this->school_id)?(int)$this->school_id:$this->school_id,
          'educator_id' => isset($this->educator_id)?(int)$this->educator_id:$this->educator_id,
          'teacher_id' => isset($this->teacher_id)?(int)$this->teacher_id:$this->teacher_id,
          'subject' => $this->subject,
          'content' => $this->content,
          'priority' => (int)$this->priority,
          'send_by_types' => $this->send_by_types,
          'created_at' => refactorCreatedAtFormat($this->created_at),

          'receivers' => ManualNotificationReceiverResource::collection($this->whenLoaded('Receivers')),
          'school' => new SchoolResource($this->whenLoaded('School')),
          'educator' => new EducatorResource($this->whenLoaded('Educator')),
          'teacher' => new TeacherResource($this->whenLoaded('Teacher')),
        ];
    }
}
