<?php

namespace Modules\SubscriptionPlan\Http\Resources;

use App\Http\Traits\PaginationResources;
use Illuminate\Http\Resources\Json\JsonResource;

class SubscriptionPlanResource extends JsonResource
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
            'name' => $this->name,
            'description' => $this->description,
            'is_paid' => (bool)$this->is_paid,
            'cost' => (float)$this->cost,
            'billing_cycle' => $this->billing_cycle,
            'billing_cycle_days' => (int)$this->billing_cycle_days,
            'type' => $this->type,
            'is_active' => (bool)$this->is_active,
            'subscription_plan_modules' => SubscriptionPlanModuleResource::collection($this->whenLoaded('SubscriptionPlanModules')),
        ];
    }
}
