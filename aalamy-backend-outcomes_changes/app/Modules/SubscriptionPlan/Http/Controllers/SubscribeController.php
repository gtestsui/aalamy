<?php

namespace Modules\SubscriptionPlan\Http\Controllers;

use App\Http\Controllers\Classes\ApiResponseClass;
use App\Http\Controllers\Classes\ApplicationModules;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Modules\SubscriptionPlan\Http\Controllers\Classes\ManagePayment\PaymentManagementFactory;
use Modules\SubscriptionPlan\Http\Controllers\Classes\PlanConstraints\Factory\PlanConstraintsManagementFactory;
use Modules\SubscriptionPlan\Http\Controllers\Classes\SubscriptionPlanServices;
use Modules\SubscriptionPlan\Http\Controllers\Classes\UserSubscribeClass;
use Modules\SubscriptionPlan\Http\DTO\SubscribedUserData;
use Modules\SubscriptionPlan\Http\Requests\Subscribe\GetMyModuleUsageDetails;
use Modules\SubscriptionPlan\Models\Module;
use Modules\SubscriptionPlan\Models\SubscriptionPlan;
use Modules\SubscriptionPlan\Models\SubscriptionPlanModule;
use Modules\User\Http\Controllers\Classes\UserServices;
use Modules\User\Models\School;
use Modules\User\Models\User;
use Modules\User\Http\Controllers\Classes\UserAccountTypeAndObjectSingleton;


class SubscribeController extends Controller
{
    //dd(trans('user::messages.QuestionTypes'));

    /**
     * if the user subscribed in paid plan he cant downgrade to free one
     *
     * if the user choice to subscribe in free plan that mean its
     * confirmed automatically
     * else choice paid plan then return paypal link
     */
    public function subscribe(Request $request,$subscriptionPlanId){
        $user = $request->user();

//        $user = auth()->guard('api')->user();
        $subscribedUserData = SubscribedUserData::fromRequest($request);
//        dd($user);
        DB::beginTransaction();
        $plan = SubscriptionPlan::findOrFail($subscriptionPlanId);
        $userSubscribeClass = new UserSubscribeClass($user);
        $userSubscribe = $userSubscribeClass->subscribe($plan);
        if(SubscriptionPlanServices::checkPlanIsFree($plan)){

            SubscriptionPlanServices::checkFoundSubscribePaidPlan($user,$userSubscribe);
            $userSubscribe = $userSubscribeClass->confirmSubscribe($userSubscribe);
            DB::commit();
            return ApiResponseClass::successResponse(['link' => 'https://link-front-page']);
            //here we redirect to front page to reload the new data of the user by his token
            //or we should return the link of that page and the front redirect to it
        }else{
            //here we should redirect to payment
            //or we should return the link of that page and the front redirect to it

            $paymentClass = PaymentManagementFactory::create('paypal');
            $redirectUrl = $paymentClass->prepareRedirectUrl($subscribedUserData,$plan,$userSubscribe);
            DB::commit();
            return ApiResponseClass::successResponse([
                'redirect_url' => $redirectUrl
            ]);
        }


    }


    public function getPaymentStatus(Request $request,$status)
    {
        DB::beginTransaction();
        $user = auth()->guard('api')->user();
        Log::channel('customized_logger')->info('user: '.json_encode($user));

        $paymentClass = PaymentManagementFactory::create('paypal');
        $realStatus = $paymentClass->getPaymentStatus($request,$status);
        DB::commit();
        if($realStatus){
            Log::channel('customized_logger')->debug('endpoint_response_status:  success');
            return redirect()->route('success.payment');
        }
        Log::channel('customized_logger')->debug('endpoint_response_status: failed error');
        return redirect()->route('failed.payment');

    }

    public function showSuccessPaymentOperation(){
        return view('SubscriptionPlan::success-payment-status');
    }

    public function showFailedPaymentOperation(){
        return view('SubscriptionPlan::failed-payment-status');
    }

    public function checkIfCanUseAssignmentEditor(Request $request){
        $user = $request->user();
        list($accountType,$accountObject) = UserServices::getAccountTypeAndObject($user);
        if($user->account_type == 'student'){
            $accountObject->load('SchoolStudent');
            if(!is_null($accountObject->SchoolStudent)){
                $school = School::where('id',$accountObject->SchoolStudent->school_id)->firstOrFail();
                $schoolUser = User::where('id',$school->user_id)->firstOrFail();
                UserAccountTypeAndObjectSingleton::flush();
                $assignmentEditorModuleClass = PlanConstraintsManagementFactory::createAssignmentEditorModule($schoolUser);
                $canUse = $assignmentEditorModuleClass->check();
            }else{
                $canUse = true;
            }

        }else{
            $assignmentEditorModuleClass = PlanConstraintsManagementFactory::createAssignmentEditorModule($user,$request->my_teacher_id);
            try {
                $canUse = $assignmentEditorModuleClass->check();
            }catch (\Exception $e){
                $canUse = false;
            }
        }
        return ApiResponseClass::successResponse([
           'can_use' => $canUse,
        ]);
    }


    public function getMyModuleUsageDetails(GetMyModuleUsageDetails $request){
        $user = $request->user();
        list($accountType,$accountObject) = UserServices::getAccountTypeAndObject($user);
        $ownerUser = User::findOrFail($accountObject->user_id);
        $userSubscribeClass = new UserSubscribeClass($ownerUser);
        $this->userSubscribe = $userSubscribeClass->getActiveSubscribe();

        //get just the modules have usage_type is by_use to hide the modules from the interface
        // if have can_use false and if  usage_type is by_limit_use ,so we don't need to hide it
        $modulesByUsage = Module::where('usage_type',configFromModule('panel.modules_usage_types.by_use',ApplicationModules::SUBSCRIPTION_PLAN_MODULE_NAME))
            ->where('type',$accountType)
            ->get();
        $modulesUsageDetails = [];
        if(is_null($this->userSubscribe)){
            foreach ($modulesByUsage as $module){
                $modulesUsageDetails[$module->name] = false;
            }

        }else{
            $subscriptionPlanModules = SubscriptionPlanModule::where('subscription_plan_id',$this->userSubscribe->subscription_plan_id)
                ->get();

            foreach ($modulesByUsage as $module){

                $subscriptionPlanModule = $subscriptionPlanModules->where('module_id',$module->id)->first();
                $modulesUsageDetails[$module->name] = is_null($subscriptionPlanModule)
                    ? false
                    : (bool)$subscriptionPlanModule->can_use;
            }
        }

        return ApiResponseClass::successResponse($modulesUsageDetails);

    }


}
