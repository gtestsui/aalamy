<?php

namespace Modules\HelpCenter\Http\Resources;

use App\Http\Traits\PaginationResources;
use App\Modules\HelpCenter\Http\Resources\UserGuideResource;
use Illuminate\Http\Resources\Json\JsonResource;

class CategoryResource extends JsonResource
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
            'name' => $this->name,
            'description' => $this->description,
            'image' => $this->image,
            'deleted' => (bool)$this->deleted,
            'deleted_at' => $this->deleted_at,
            'user_guides' => UserGuideResource::collection($this->whenLoaded('UserGuides')),
        ];
    }
}
