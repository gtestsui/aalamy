<?php

namespace Modules\SubscriptionPlan\Http\Controllers\Classes\ManagePayment;


use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;
use Modules\SubscriptionPlan\Http\DTO\SubscribedUserData;
use Modules\SubscriptionPlan\Models\SubscriptionPlan;
use Modules\SubscriptionPlan\Models\UserSubscription;

interface ManagePaymentInterface
{

    /**
     * @return string
     */
    public function prepareRedirectUrl(SubscribedUserData $subscribedUserData,
                                       SubscriptionPlan $plan,
                                       UserSubscription $userSubscribe):string;

    /**
     * @return View
     */
    public function getPaymentStatus(Request $request,$status);

}
