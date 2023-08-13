<?php

namespace Modules\Level\Http\Resources;

use App\Http\Traits\PaginationResources;
use App\Modules\HelpCenter\Http\Resources\UserGuideResource;
use App\Modules\User\Http\Resources\StudentResource;
use Illuminate\Http\Resources\Json\JsonResource;
use Modules\User\Http\Resources\UserResource;

class BaseLevelSubjectRuleResource extends JsonResource
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
            'base_level_subject_id' => (int)$this->base_level_subject_id,
            'requires_failure' => (bool)$this->requires_failure,
            'enter_the_overall_total' => (bool)$this->enter_the_overall_total,
            'optional' => (bool)$this->optional,
            'max_degree' => (int)$this->max_degree,
            'min_degree' => (int)$this->min_degree,
            'failure_point' => (int)$this->failure_point,
            'its_one_field' => (bool)$this->its_one_field,
            'classes_count_at_week' => (int)$this->classes_count_at_week,

        ];
    }
}
