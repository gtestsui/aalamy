<?php

namespace Modules\Level\Http\Resources;

use App\Http\Traits\PaginationResources;
use App\Modules\HelpCenter\Http\Resources\UserGuideResource;
use App\Modules\User\Http\Resources\StudentResource;
use Illuminate\Http\Resources\Json\JsonResource;
use Modules\User\Http\Resources\UserResource;

class BaseLevelSubjectResource extends JsonResource
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
            'base_level_id' => isset($this->base_level_id)?(int)$this->base_level_id:$this->base_level_id,
            'base_subject_id' => isset($this->base_subject_id)?(int)$this->base_subject_id:$this->base_subject_id,
            'deleted' => (bool)$this->deleted,
            'deleted_at' => $this->deleted_at,
            'created_at' => refactorCreatedAtFormat($this->created_at),

            'base_level' => new BaseLevelResource($this->whenLoaded('BaseLevel')),
            'base_subject' => new BaseSubjectResource($this->whenLoaded('BaseSubject')),
            'rule' => new BaseLevelSubjectRuleResource($this->whenLoaded('Rule')),


        ];
    }
}
