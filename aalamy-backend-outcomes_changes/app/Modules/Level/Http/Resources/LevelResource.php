<?php

namespace Modules\Level\Http\Resources;

use App\Http\Traits\PaginationResources;
use App\Modules\HelpCenter\Http\Resources\UserGuideResource;
use App\Modules\User\Http\Resources\StudentResource;
use Illuminate\Http\Resources\Json\JsonResource;
use Modules\User\Http\Resources\UserResource;

class LevelResource extends JsonResource
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
            'base_level_id' => isset($this->base_level_id)?(int)$this->base_level_id:$this->base_level_id,
            'name' => $this->name,
            'type' => $this->type,
            'deleted' => (bool)$this->deleted,
            'deleted_at' => $this->deleted_at,
            'created_at' => refactorCreatedAtFormat($this->created_at),
            'user' => new UserResource($this->whenLoaded('User')),
            'level_subjects' => LevelSubjectResource::collection($this->whenLoaded('LevelSubjects')),

//            'user_guides' => UserGuideResource::collection($this->whenLoaded('UserGuides')),
        ];
    }
}
