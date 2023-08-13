<?php

namespace Modules\Address\Http\Resources;

use App\Http\Traits\PaginationResources;
use Illuminate\Http\Resources\Json\JsonResource;

class StateResource extends JsonResource
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
            'name_en' => $this->name_en,
            'name_ar' => $this->name_ar,
            'country_id' => (int)$this->country_id,
            'created_at' => refactorCreatedAtFormat($this->created_at),
            'country' => new CountryResource($this->whenLoaded('Country')),
        ];
    }
}
