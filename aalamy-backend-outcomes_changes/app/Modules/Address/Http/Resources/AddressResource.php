<?php

namespace Modules\Address\Http\Resources;

use App\Http\Traits\PaginationResources;
use Illuminate\Http\Resources\Json\JsonResource;

class AddressResource extends JsonResource
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
            'country_id' => (int)$this->country_id,
            'state_id' => (int)$this->state_id,
//            'city_id' => $this->city_id,
            'city' => $this->city,
            'street' => $this->street,
            'country' => new CountryResource($this->whenLoaded('Country')),
            'state' => new StateResource($this->whenLoaded('State')),
//            'city' => $this->whenLoaded('City'),
        ];
    }
}
