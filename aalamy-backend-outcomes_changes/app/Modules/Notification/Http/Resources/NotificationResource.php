<?php

namespace Modules\Notification\Http\Resources;

use App\Http\Traits\PaginationResources;
use Illuminate\Http\Resources\Json\JsonResource;
use Modules\User\Http\Resources\UserResource;

class NotificationResource extends JsonResource
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
          'data' => $this->data,
          'read_date' => $this->read_date,
          'user' => new UserResource($this->whenLoaded('User')),
          'created_at' => refactorCreatedAtFormat($this->created_at),
//          'type' => new NotificationTypeResource($this->whenLoaded('Type')),
        ];
    }
}
