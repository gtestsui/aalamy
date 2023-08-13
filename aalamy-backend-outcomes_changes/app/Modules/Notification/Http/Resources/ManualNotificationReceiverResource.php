<?php

namespace Modules\Notification\Http\Resources;

use App\Http\Traits\PaginationResources;
use Illuminate\Http\Resources\Json\JsonResource;
use Modules\User\Http\Resources\UserResource;

class ManualNotificationReceiverResource extends JsonResource
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
          'manual_notification_id' => isset($this->manual_notification_id)?(int)$this->manual_notification_id:$this->manual_notification_id,
          'user' => new UserResource($this->whenLoaded('User')),
          'manual_notification' => new ManualNotificationResource($this->whenLoaded('ManualNotification')),
        ];
    }
}
