<?php

namespace Modules\SubscriptionPlan\Http\Resources;

use App\Http\Traits\PaginationResources;
use Illuminate\Http\Resources\Json\JsonResource;
use Modules\User\Http\Resources\UserResource;

class UserSubscriptionResource extends JsonResource
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
            'subscription_plan_id' => isset($this->subscription_plan_id)?(int)$this->subscription_plan_id:$this->subscription_plan_id,
            'start_date' => $this->start_date,
            'end_date' => $this->end_date,
            'is_active' => (bool)$this->is_active,
            'is_confirmed' => (bool)$this->is_confirmed,
            'user' => new UserResource($this->whenLoaded('User')),
            'subscription_plan' => new SubscriptionPlanResource($this->whenLoaded('SubscriptionPlan')),
        ];
    }
}
