<?php

namespace Modules\ClassModule\Http\Resources;

use App\Http\Traits\PaginationResources;
use Illuminate\Http\Resources\Json\JsonResource;
use Modules\Level\Http\Resources\LevelResource;

class ClassResource extends JsonResource
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
            'level_id' => isset($this->level_id)?(int)$this->level_id:$this->level_id,
            'name' => $this->name,
            'created_at' => refactorCreatedAtFormat($this->created_at),
            'deleted' => (bool)$this->deleted,
            'deleted_at' => $this->deleted_at,
            'level' => new LevelResource($this->whenLoaded('Level')),
            'class_infos' => ClassInfoResource::collection($this->whenLoaded('ClassInfos')),

        ];
    }
}
