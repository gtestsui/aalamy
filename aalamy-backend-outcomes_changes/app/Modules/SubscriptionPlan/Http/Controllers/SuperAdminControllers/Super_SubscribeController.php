<?php

namespace Modules\SubscriptionPlan\Http\Controllers\SuperAdminControllers;

use App\Exceptions\ErrorMsgException;
use App\Http\Controllers\Classes\ApiResponseClass;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Modules\SubscriptionPlan\Http\Controllers\Classes\ManagePayment\PaypalClass;
use Modules\SubscriptionPlan\Http\Controllers\Classes\ManagePayment\PaymentManagementFactory;
use Modules\SubscriptionPlan\Http\Controllers\Classes\SubscriptionPlanServices;
use Modules\SubscriptionPlan\Http\Controllers\Classes\UserSubscribeClass;
use Modules\SubscriptionPlan\Http\DTO\SubscribedUserData;
use Modules\SubscriptionPlan\Http\Resources\UserSubscriptionResource;
use Modules\SubscriptionPlan\Models\SubscriptionPlan;
use Modules\SubscriptionPlan\Models\UserSubscription;
use PayPal\Api\Payment;
use PayPal\Api\PaymentExecution;

class Super_SubscribeController extends Controller
{

    public function paginate(Request $request){
        $userSubscriptions = UserSubscription::with(['User'=>function($query){
            return $query->with(['Educator','School']);
        },'SubscriptionPlan'])
            ->paginate(config('panel.admin_paginate_num'));
        return ApiResponseClass::successResponse(UserSubscriptionResource::collection($userSubscriptions));
    }

    public function getBySubscriptionPlanId(Request $request,$subscription_plan_id){
        $userSubscriptions = UserSubscription::search($request->key,[],[
                'User'
            ])
            ->where('subscription_plan_id',$subscription_plan_id)
            ->with(['User'=>function($query){
                return $query->with(['Educator','School']);
            },'SubscriptionPlan'])
            ->paginate(config('panel.admin_paginate_num'));
        return ApiResponseClass::successResponse(UserSubscriptionResource::collection($userSubscriptions));

    }

}
