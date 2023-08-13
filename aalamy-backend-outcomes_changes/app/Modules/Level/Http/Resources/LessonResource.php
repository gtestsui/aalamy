<?php

namespace Modules\Level\Http\Resources;

use App\Http\Traits\PaginationResources;
use Illuminate\Http\Resources\Json\JsonResource;
use Modules\User\Http\Resources\UserResource;

class LessonResource extends JsonResource
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
            'unit_id' => isset($this->unit_id)?(int)$this->unit_id:$this->unit_id,
            'name' => $this->name,
            'type' => $this->type,
            'deleted' => (bool)$this->deleted,
            'deleted_at' => $this->deleted_at,
            'created_at' => refactorCreatedAtFormat($this->created_at),
            'user' => new UserResource($this->whenLoaded('User')),
            'unit' => new UnitResource($this->whenLoaded('Unit')),

//            'user_guides' => UserGuideResource::collection($this->whenLoaded('UserGuides')),
        ];
    }
}
