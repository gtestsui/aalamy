<?php


namespace Modules\SubscriptionPlan\Http\DTO;


use App\Http\Controllers\Classes\ServicesClass;
use App\Http\Controllers\DTO\Parents\ObjectData;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Modules\SubscriptionPlan\Http\Controllers\Classes\SubscriptionPlanServices;
use Modules\SubscriptionPlan\Models\SubscriptionPlan;

final class SubscriptionPlanData extends ObjectData
{
    public ?int      $id=null;
    public string    $name;
    public string    $description;
    public bool      $is_paid;
    public ?float    $cost;
    public ?string   $billing_cycle;
    public ?int      $billing_cycle_days;
    public  string   $type;
    public array    $modules;
//    public ?Carbon   $created_at;

    public static function fromRequest(Request $request,?SubscriptionPlan $plan=null): self
    {
        $fixedCountOfDays = SubscriptionPlanServices::checkBillingCycleIsFixed($request->billing_cycle);
        return new self([
            'name' => $request->name,
            'description' => $request->description,
            'is_paid' => (bool)$request->is_paid,
            'cost' => (float)$request->cost,
            'billing_cycle' => $request->billing_cycle,
            'billing_cycle_days' => $fixedCountOfDays?(int)$request->billing_cycle_days:(int)config('SubscriptionPlan.panel.billing_cycles_in_days.'.$request->billing_cycle),
            'type' => isset($plan)?$plan->type:$request->type,
            'modules' => $request->modules,
//            'category_id' => $request->category_id?(int) $request->category_id:$request->category_id,


        ]);
    }

    public function allWithoutRelations(): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'description' => $this->description,
            'is_paid' => $this->is_paid,
            'cost' => $this->cost,
            'billing_cycle' => $this->billing_cycle,
            'billing_cycle_days' => $this->billing_cycle_days,
            'type' => $this->type,
        ];
    }
}
