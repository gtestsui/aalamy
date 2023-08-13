<?php

namespace Modules\Level\Http\Resources;

use App\Http\Traits\PaginationResources;
use App\Modules\HelpCenter\Http\Resources\UserGuideResource;
use App\Modules\User\Http\Resources\StudentResource;
use Illuminate\Http\Resources\Json\JsonResource;
use Modules\User\Http\Resources\UserResource;

class LevelSubjectResource extends JsonResource
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
            'subject_id' => isset($this->subject_id)?(int)$this->subject_id:$this->subject_id,
            'deleted' => (bool)$this->deleted,
            'deleted_at' => $this->deleted_at,
            'created_at' => refactorCreatedAtFormat($this->created_at),

            'level' => $this->relationLoaded('Level')
                ?new LevelResource($this->Level)
                :new LevelResource($this->whenLoaded('LevelEvenItsDeleted')),

            'subject' => $this->relationLoaded('Subject')
                ?new SubjectResource($this->Subject)
                :new SubjectResource($this->whenLoaded('SubjectEvenItsDeleted')),


        ];
    }
}
