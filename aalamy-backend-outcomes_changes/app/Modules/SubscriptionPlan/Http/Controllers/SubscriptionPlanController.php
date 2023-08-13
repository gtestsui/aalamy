<?php

namespace Modules\SubscriptionPlan\Http\Controllers;

use App\Http\Controllers\Classes\ApiResponseClass;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Modules\SubscriptionPlan\Http\Controllers\Classes\SubscriptionPlanServices;
use Modules\SubscriptionPlan\Http\DTO\SubscriptionPlanData;
use Modules\SubscriptionPlan\Http\Requests\SubscriptionPlan\StoreSubscriptionPlanRequest;
use Modules\SubscriptionPlan\Http\Requests\SubscriptionPlan\UpdateSubscriptionPlanRequest;
use Modules\SubscriptionPlan\Http\Resources\SubscriptionPlanResource;
use Modules\SubscriptionPlan\Models\SubscriptionPlan;
use Modules\SubscriptionPlan\Models\SubscriptionPlanModule;

class SubscriptionPlanController extends Controller
{


    public function index(){
        $plans = SubscriptionPlan::active()
            ->with('SubscriptionPlanModules')
            ->get();
        return ApiResponseClass::successResponse(SubscriptionPlanResource::collection($plans));
    }


    public function getPlansByAccountType(Request $request){
        $user = $request->user();
        $plans = SubscriptionPlan::where('type',$user->account_type)
            ->with('SubscriptionPlanModules')
            ->active()->get();
        return ApiResponseClass::successResponse(SubscriptionPlanResource::collection($plans));

    }

    public function getPlansByType($type){
        SubscriptionPlanServices::checkSupportedPlanType($type);
        $plans = SubscriptionPlan::where('type',$type)
            ->with('SubscriptionPlanModules')
            ->active()->get();
        return ApiResponseClass::successResponse(SubscriptionPlanResource::collection($plans));
    }

//    public function getPlansByType($type){
//        SubscriptionPlanServices::checkSupportedPlanType($type);
//        $plans = SubscriptionPlan::where('type',$type)
//            ->with('SubscriptionPlanModules')
//            ->active()->get();
//        return ApiResponseClass::successResponse(SubscriptionPlanResource::collection($plans));
//    }
//
//    public function store(StoreSubscriptionPlanRequest $request){
//        try{
//            DB::beginTransaction();
//
//            $planData = SubscriptionPlanData::fromRequest($request);
//            $plan = SubscriptionPlan::create($planData->allWithoutRelations());
//            SubscriptionPlanServices::addMoreThanModuleToPlan($plan,$planData->moduleIds);
//
//            DB::commit();
//            return ApiResponseClass::successResponse(new SubscriptionPlanResource($plan));
//        }catch (\Exception $e){
//            DB::rollBack();
//            return ApiResponseClass::errorMsgResponse($e->getMessage());
//        }
//
//    }
//
//    public function update(UpdateSubscriptionPlanRequest $request,$id){
//        try{
//            DB::beginTransaction();
//
//            $planData = SubscriptionPlanData::fromRequest($request);
//            $plan = SubscriptionPlan::findOrFail($id);
//            $plan->update($planData->initializeForUpdate($planData));
//            SubscriptionPlanServices::updateMoreThanModuleInPlan($plan,$planData->moduleIds);
//
//            DB::commit();
//            return ApiResponseClass::successResponse(new SubscriptionPlanResource($plan));
//        }catch (\Exception $e){
//            DB::rollBack();
//            return ApiResponseClass::errorMsgResponse($e->getMessage());
//        }
//    }
//
//    public function destroy(Request $request,$id){
//        $plan = SubscriptionPlan::findOrFail($id);
//        $plan->deleted = 1;
//        $plan->save();
//        return ApiResponseClass::deletedResponse();
//    }

}
