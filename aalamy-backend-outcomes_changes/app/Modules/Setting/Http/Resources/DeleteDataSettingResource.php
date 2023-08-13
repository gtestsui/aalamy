<?php

namespace Modules\Setting\Http\Resources;

use App\Http\Traits\PaginationResources;
use Illuminate\Http\Resources\Json\JsonResource;

class DeleteDataSettingResource extends JsonResource
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
            'time_for_force_delete_data' => $this->time_for_force_delete_data,
            'type' => $this->type,

        ];
    }
}
