<?php

namespace Modules\SubscriptionPlan\Http\Resources;

use App\Http\Traits\PaginationResources;
use Illuminate\Http\Resources\Json\JsonResource;

class SubscriptionPlanModuleResource extends JsonResource
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
            'subscription_plan_id' => (int)$this->subscription_plan_id,
            'module_id' => (int)$this->module_id,
            'number' => isset($this->number)?(int)$this->number:null,
            'can_use' => isset($this->can_use)?(bool)$this->can_use:null,

            'module' => new ModuleResource($this->whenLoaded('Module')),
        ];
    }
}
