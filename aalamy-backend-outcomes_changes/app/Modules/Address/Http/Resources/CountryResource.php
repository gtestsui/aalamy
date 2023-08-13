<?php

namespace Modules\Address\Http\Resources;

use App\Http\Traits\PaginationResources;
use Illuminate\Http\Resources\Json\JsonResource;

class CountryResource extends JsonResource
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
            'created_at' => refactorCreatedAtFormat($this->created_at),
        ];
    }
}
