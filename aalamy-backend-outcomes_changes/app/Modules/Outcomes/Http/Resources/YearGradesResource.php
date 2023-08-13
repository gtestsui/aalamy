<?php

namespace Modules\Outcomes\Http\Resources;

use App\Http\Traits\PaginationResources;
use App\Modules\HelpCenter\Http\Resources\UserGuideResource;
use App\Modules\User\Http\Resources\SchoolResource;
use App\Modules\User\Http\Resources\StudentResource;
use Illuminate\Http\Resources\Json\JsonResource;
use Modules\Level\Http\Resources\BaseLevelResource;
use Modules\Level\Http\Resources\BaseSubjectResource;
use Modules\Level\Http\Resources\LevelResource;
use Modules\User\Http\Resources\UserResource;

class YearGradesResource extends JsonResource
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
            'base_level_id' => (int)$this->base_level_id,
            'base_subject_id' => (int)$this->base_subject_id,
            'writable_subject_name' => $this->writable_subject_name,
            'order' => (int)$this->order,
            'max_degree' => isset($this->max_degree)
                ?(int)$this->max_degree
                :null,
            'its_one_mark' => (boolean)$this->its_one_mark,
        	'its_grand_total' => (boolean)$this->its_grand_total,
        	'its_final_total' => (boolean)$this->its_final_total,
        

            'base_subject' => new BaseSubjectResource($this->whenLoaded('BaseSubject')),
            'base_level' => new BaseLevelResource($this->whenLoaded('BaseLevel')),
            'marks' => MarkResource::collection($this->whenLoaded('Marks')),

        ];
    }
}
