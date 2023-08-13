<?php


namespace Modules\SubscriptionPlan\Http\Controllers\Classes\PlanConstraints;



use App\Exceptions\ErrorMsgException;
use App\Http\Controllers\Classes\ApplicationModules;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Modules\SubscriptionPlan\Http\Controllers\Classes\UserSubscribeClass;
use Modules\SubscriptionPlan\Models\Module;
use Modules\SubscriptionPlan\Models\SubscriptionPlanModule;
use Modules\User\Models\Teacher;
use Modules\User\Models\User;

abstract class BasePlanConstraintsClass
{

    protected Module $module;
    protected User $user;
    protected  $userSubscribe;
    protected  $createdByOther = false;


    /**
     * to prevent call this class using new keyword (new AssignmentCountModuleClass());
     */
    public function __construct($calledStatic=false) {
        if(!$calledStatic)
            throw new ErrorMsgException('cant initialize class '.class_basename($this).' use createByOwner or createByOther');
    }

    /**
     * @throws ErrorMsgException
     * @return bool
     */
    abstract public function check();

    /**
     * this function will initialize the module we have targeted it
     */
    protected function setModule($moduleName){
        $this->module = Module::where('identify',$moduleName)
            ->firstOrFail();

    }

    /**
     * this function will return the quantity of the selected module and userSubscribe from the plan
     * @return integer
     * @throws ModelNotFoundException
     */
    protected function getAvailabeQuantity(){
        $subscriptionPlanModule = SubscriptionPlanModule::where('module_id',$this->module->id)
            ->where('subscription_plan_id',$this->userSubscribe->subscription_plan_id)
            ->firstOrFail();
        return $subscriptionPlanModule->number;
    }

    /**
     * @return boolean
     */
    protected function getCanUseStatus(){
        $subscriptionPlanModule = SubscriptionPlanModule::where('module_id',$this->module->id)
            ->where('subscription_plan_id',$this->userSubscribe->subscription_plan_id)
            ->firstOrFail();
        return $subscriptionPlanModule->can_use;
    }


    /**
     * check if user has Subscribed
     * @throws ErrorMsgException
     */
    protected function checkAndSetUserSubscribe(){
        $userSubscribeClass = new UserSubscribeClass($this->user);
        $this->userSubscribe = $userSubscribeClass->getActiveSubscribe();
        if(is_null($this->userSubscribe))
            throw new ErrorMsgException(transMsg('your_subscription_time_has_done',ApplicationModules::SUBSCRIPTION_PLAN_MODULE_NAME));

    }


}
