<?php

namespace Modules\Notification\Http\Resources;

use App\Http\Traits\PaginationResources;
use Illuminate\Http\Resources\Json\JsonResource;
use Modules\User\Http\Resources\UserResource;

class NotificationTypeResource extends JsonResource
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
          'name_en' => $this->name_en,
          'name_ar' => $this->name_ar,
          'type_num' => (int)$this->type_num,
        ];
    }
}
