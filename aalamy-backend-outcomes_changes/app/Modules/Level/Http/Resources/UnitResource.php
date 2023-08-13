<?php

namespace Modules\Level\Http\Resources;

use App\Http\Traits\PaginationResources;
use Illuminate\Http\Resources\Json\JsonResource;
use Modules\User\Http\Resources\UserResource;

class UnitResource extends JsonResource
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
            'level_subject_id' => isset($this->level_subject_id)?(int)$this->level_subject_id:$this->level_subject_id,
            'name' => $this->name,
            'type' => $this->type,
            'deleted' => (bool)$this->deleted,
            'deleted_at' => $this->deleted_at,
            'user' => new UserResource($this->whenLoaded('User')),
            'level_subject' => new LevelSubjectResource($this->whenLoaded('LevelSubject')),
            'created_at' => refactorCreatedAtFormat($this->created_at),


//            'user_guides' => UserGuideResource::collection($this->whenLoaded('UserGuides')),
        ];
    }
}
