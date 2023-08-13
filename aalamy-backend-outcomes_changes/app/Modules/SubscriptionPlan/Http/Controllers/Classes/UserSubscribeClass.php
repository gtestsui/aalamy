<?php


namespace Modules\SubscriptionPlan\Http\Controllers\Classes;



use App\Exceptions\ErrorMsgException;
use Carbon\Carbon;
use Modules\SubscriptionPlan\Models\SubscriptionPlan;
use Modules\SubscriptionPlan\Models\UserSubscription;
use Modules\User\Models\User;

class UserSubscribeClass
{

    protected User $user;
    public function __construct(User $user)
    {
        $this->user = $user;

    }


    public function getActiveSubscribe(){
        return UserSubscription::where('user_id',$this->user->id)
            ->active()
            ->confirmed()
            ->available()
            ->first();
    }


    public function subscribeEducatorFreePlan(){
        $this->subscribeFreePlanByPlanType('educator');

    }

    public function subscribeSchoolFreePlan(){
        $this->subscribeFreePlanByPlanType('school');

    }

    private function subscribeFreePlanByPlanType($planType){
        $plan = SubscriptionPlan::paid(false)
            ->where('type',$planType)->first();
        if(is_null($plan))
            throw new ErrorMsgException('error while initialize free plan' );

        $userSubscribe = $this->subscribe($plan);

        $userSubscribe = $this->confirmSubscribe($userSubscribe);

    }

    public function subscribe( SubscriptionPlan $plan): UserSubscription
    {
        SubscriptionPlanServices::checkSubscribePlanAuthorization($this->user,$plan);
        list($startDate,$endDate) = SubscriptionPlanServices::initializeSubscribeDate($plan);
        $subscription = $this->createSubscribe($plan,$startDate,$endDate);
        return $subscription;
    }

    public function createSubscribe(SubscriptionPlan $plan,$startDate,$endDate):UserSubscription
    {
        $subscription = UserSubscription::create([
            'user_id' => $this->user->id,
            'subscription_plan_id' => $plan->id,
            'start_date' => $startDate,
            'end_date' => $endDate,
        ]);
        return $subscription;
    }


    public function confirmSubscribe(UserSubscription $userSubscribe):UserSubscription
    {
        $userSubscribe->update([
            'is_confirmed' => 1,
            'is_active' => 1
        ]);
        $this->unActiveOldSubscriptions($userSubscribe->id);

        return $userSubscribe;
    }

    /*public function upgradeSubscribe( SubscriptionPlan $plan):UserSubscription
    {
        SubscriptionPlanServices::checkUpgradeSubscribeAuthorization($this->user,$plan);
        list($startDate,$endDate) = SubscriptionPlanServices::initializeSubscribeDate($plan);
        $subscription = $this->createSubscribe($plan,$startDate,$endDate);
        return $subscription;
    }*/

    /*public function confirmUpgrade(UserSubscription $userSubscribe):UserSubscription
    {
        $userSubscribe->update([
            'is_confirmed' => 1,
            'is_active' => 1
        ]);
        $this->unActiveOldSubscriptions($userSubscribe->id);
        return $userSubscribe;
    }*/

    public function unActiveOldSubscriptions($exceptId=null){
        UserSubscription::where('user_id',$this->user->id)
            ->where('id','!=',$exceptId)
            ->update([
                'is_active' => 0
            ]);
    }





}
