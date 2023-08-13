<?php

namespace Modules\SubscriptionPlan\Traits;
use App\Http\Controllers\Classes\ApplicationModules;
use Carbon\Carbon;
use Carbon\CarbonTimeZone;

trait ValidationAttributesTrans
{
    private $moduleName = ApplicationModules::SUBSCRIPTION_PLAN_MODULE_NAME;
    public function attributes(){
        return [
            'name' => transValidationParameter('name',$this->moduleName),
            'description' => transValidationParameter('description',$this->moduleName),
            'is_paid' => transValidationParameter('is_paid',$this->moduleName),
            'cost' => transValidationParameter('cost',$this->moduleName),
            'billing_cycle' => transValidationParameter('billing_cycle',$this->moduleName),
            'billing_cycle_days' => transValidationParameter('billing_cycle_days',$this->moduleName),
            'type' => transValidationParameter('type',$this->moduleName),


            'number' => transValidationParameter('number',$this->moduleName),

        ];
    }
}
