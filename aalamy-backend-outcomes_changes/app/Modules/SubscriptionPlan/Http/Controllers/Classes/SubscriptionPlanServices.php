<?php


namespace Modules\SubscriptionPlan\Http\Controllers\Classes;



use App\Exceptions\ErrorMsgException;
use App\Http\Controllers\Classes\ApplicationModules;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Modules\SubscriptionPlan\Http\DTO\SubscriptionPlanData;
use Modules\SubscriptionPlan\Models\Module;
use Modules\SubscriptionPlan\Models\SubscriptionPlan;
use Modules\SubscriptionPlan\Models\SubscriptionPlanModule;
use Modules\SubscriptionPlan\Models\UserSubscription;
use Modules\User\Models\User;

class SubscriptionPlanServices
{

    public static function checkSupportedPlanType($type): Void
    {
        $arrayTypes = config('SubscriptionPlan.panel.subscription_plan_types');
        if(!in_array($type,$arrayTypes))
            throw new ErrorMsgException(transMsg('invalid_plan_type',ApplicationModules::SUBSCRIPTION_PLAN_MODULE_NAME));
    }

    public static function checkSubscribePlanAuthorization(User $user,SubscriptionPlan $plan)
    {
        Self::checkPlanTypeAuthorization($user,$plan);
//        Self::checkFoundActiveSubscribe($user);
    }


    public static function checkPlanTypeAuthorization(User $user,SubscriptionPlan $plan): Void
    {
        if($user->account_type != $plan->type)
            throw new ErrorMsgException(transMsg('plan_type_authorization',ApplicationModules::SUBSCRIPTION_PLAN_MODULE_NAME));
    }

    public static function checkFoundActiveSubscribe(User $user): Void
    {
        $oldSubscription = UserSubscription::where('user_id',$user->id)
            ->active()->first();
        if(!is_null($oldSubscription))
            throw new ErrorMsgException(transMsg('found_active_subscribe',ApplicationModules::SUBSCRIPTION_PLAN_MODULE_NAME));
    }

    public static function checkFoundSubscribePaidPlan(User $user, UserSubscription $userSubscribe){
        $oldPaidSubscription = UserSubscription::where('user_id',$user->id)
            ->where('id','!=',$userSubscribe->id)
            ->first();
        if(!is_null($oldPaidSubscription))
            throw new ErrorMsgException(transMsg('found_paid_subscribe',ApplicationModules::SUBSCRIPTION_PLAN_MODULE_NAME));

    }

    public static function checkBillingCycleIsFixed($billing_cycle){
        return $billing_cycle==config('SubscriptionPlan.panel.billing_cycles.fixed_count_of_days')?true:false;
    }

    public static function checkPlanIsFree(SubscriptionPlan $plan){
        return !$plan->is_paid?true:false;
    }

    public static function checkPlanIsPaid(SubscriptionPlan $plan){
        return $plan->is_paid?true:false;
    }


    public static function initializeSubscribeDate(SubscriptionPlan $plan)
    {
        $days = Self::getSubscriptionPlanPeriodInDays($plan);
        $startDate = Carbon::now();
        $endDate = Carbon::now()->addDays($days);
        return [$startDate,$endDate];
    }

    public static function getSubscriptionPlanPeriodInDays(SubscriptionPlan $plan): int
    {
        if($plan->billing_cycle == config('SubscriptionPlan.panel.billing_cycles.fixed_count_of_days'))
            return $plan->billing_cycle_days;

        return config("SubscriptionPlan.panel.billing_cycles_in_days.{$plan->billing_cycle}");

    }

    public static function addMoreThanModuleToPlan(SubscriptionPlan $plan,?array $modules){
        if(isset($modules)){
            $moduleIds = array_column($modules,'id');
            $modulesObjectsFromData = Module::active()
                ->whereIn('id',$moduleIds)
                ->where('type',$plan->type)
                ->get();
            foreach ($modules as $moduleData){
                $moduleObject = $modulesObjectsFromData->where('id',$moduleData['id'])->first();
                Self::addModuleToPlan($plan,$moduleData,$moduleObject);
            }
        }
    }

    public static function addModuleToPlan(SubscriptionPlan $plan,$moduleData,Module $moduleObject){
        $number = null;
        $can_use = null;
        if($moduleObject->usage_type==configFromModule('panel.modules_usage_types.by_limit_number',ApplicationModules::SUBSCRIPTION_PLAN_MODULE_NAME)){
            if(!isset($moduleData['number']))
                throw new ErrorMsgException('number is required when the feature limited');
            $number = $moduleData['number'];
        }elseif($moduleObject->usage_type==configFromModule('panel.modules_usage_types.by_use',ApplicationModules::SUBSCRIPTION_PLAN_MODULE_NAME)){
            if(!isset($moduleData['can_use']))
                throw new ErrorMsgException('can_use is required when the feature limited by usage');
            $can_use = $moduleData['can_use'];
        }

        return SubscriptionPlanModule::create([
            'subscription_plan_id' =>  $plan->id,
            'module_id' =>  $moduleData['id'],
            'number' =>  $number,
            'can_use' =>  $can_use,
        ]);
    }

    public static function updateMoreThanModuleInPlan(SubscriptionPlan $plan,?array $modules){

        if(isset($modules)){
            foreach ($modules as $moduleData){
                $subscriptionPlanModule = SubscriptionPlanModule::where('module_id',$moduleData['id'])
                    ->where('subscription_plan_id',$plan->id)->first();
                if(!is_null($subscriptionPlanModule)){
                    $subscriptionPlanModule->update([
                        'number'=>$moduleData['number'],
                        'can_use'=>$moduleData['can_use'],
                    ]);
                }
            }
        }
    }

//    public static function updateMoreThanModuleInPlan(SubscriptionPlan $plan,?array $moduleIds){
//
//        if(isset($moduleIds)){
//            $keepIdsAwayFromDelete = [];
//            foreach ($moduleIds as $moduleId){
//                $subscriptionPlanModule = SubscriptionPlanModule::where('module_id',$moduleId)
//                    ->where('subscription_plan_id',$plan->id)->first();
//                if(is_null($subscriptionPlanModule)){
//                    $subscriptionPlanModule = Self::addModuleToPlan($plan,$moduleId);
//                }
//                $keepIdsAwayFromDelete[] = $subscriptionPlanModule->id;
//            }
//            SubscriptionPlanModule::where('subscription_plan_id',$plan->id)
//                ->whereNotIn('id',$keepIdsAwayFromDelete)->delete();
//        }
//    }

    public static function decryptReturnedDataFromPayment(Request $request){
        $decryptedUserSubId = Self::decrypt($request->user_sub_id);
        $request->merge([
            'user_subscribe_id' => $decryptedUserSubId
        ]);
    }

    public static function encrypt($value){
        return encrypt($value,config('SubscriptionPlan.panel.encrypt_method'));
    }

    public static function decrypt($encryptedValue){
        try {
            return decrypt($encryptedValue, config('SubscriptionPlan.panel.encrypt_method'));
        }catch (\Exception $e){
            return view('SubscriptionPlan::failed-payment-status',[
                'error' => 'invalid while decrypt'
            ]);
        }
    }


    public static function checkAllRequiredModulesHasBeenIntialized(SubscriptionPlanData $planData){
        if($planData->type == 'educator'){
            $requiredModuleIds = Module::where('type','educator')
                ->active()
                ->pluck('id')
                ->toArray();
        }else{
            $requiredModuleIds = Module::where('type','school')
                ->active()
                ->pluck('id')
                ->toArray();
        }
        $moduleIdsFromRequest = array_column($planData->modules,'id');
        //get the shared ids between required module and the module in request
        $sharedModuleIds = array_intersect($requiredModuleIds,$moduleIdsFromRequest);

        if(count($requiredModuleIds) > count($sharedModuleIds))
            throw new ErrorMsgException('there is an required modules should been intialized');

    }





}
