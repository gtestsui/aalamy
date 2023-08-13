<?php

namespace App\Modules\HelpCenter\Http\Resources;

use App\Http\Traits\PaginationResources;
use Illuminate\Http\Resources\Json\JsonResource;
use Modules\HelpCenter\Http\Resources\CategoryResource;

class UserGuideResource extends JsonResource
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
            'category_id' => isset($this->category_id)?(int)$this->category_id:$this->category_id,
            'title' => $this->title,
            'description' => $this->description,
            'user_types' => $this->user_types,
            'date' => $this->date,
            'deleted' => (bool)$this->deleted,
            'deleted_at' => $this->deleted_at,
            'category' => new CategoryResource($this->whenLoaded('Category')),
            'images' => $this->whenLoaded('Images'),
            'videos' => $this->whenLoaded('Videos'),

        ];
    }
}
