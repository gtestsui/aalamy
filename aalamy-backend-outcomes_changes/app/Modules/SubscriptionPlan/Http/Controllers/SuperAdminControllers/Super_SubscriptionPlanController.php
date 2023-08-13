<?php

namespace Modules\SubscriptionPlan\Http\Controllers\SuperAdminControllers;

use App\Http\Controllers\Classes\ApiResponseClass;
use App\Http\Controllers\Classes\RequestServicesClass;
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

class Super_SubscriptionPlanController extends Controller
{

    public function index(Request $request){

        $plans = SubscriptionPlan::search($request->key)/*active()
//            ->with('SubscriptionPlanModules')*/
            ->get();

        return ApiResponseClass::successResponse(SubscriptionPlanResource::collection($plans));
    }

    public function show($id){
        $plan = SubscriptionPlan::/*active()
            ->*/with('SubscriptionPlanModules.Module')
            ->findOrFail($id);

        return ApiResponseClass::successResponse(new SubscriptionPlanResource($plan));
    }

    public function activateOrUnActivate(Request $request,$id){
        $plan = SubscriptionPlan::findOrFail($id);
        $plan->activateOrUnActivate();
        return ApiResponseClass::successResponse(new SubscriptionPlanResource($plan));
    }

    public function store(StoreSubscriptionPlanRequest $request){
        try{
            DB::beginTransaction();

            $planData = SubscriptionPlanData::fromRequest($request);
            SubscriptionPlanServices::checkAllRequiredModulesHasBeenIntialized($planData);
            $plan = SubscriptionPlan::create($planData->allWithoutRelations());
            SubscriptionPlanServices::addMoreThanModuleToPlan($plan,$planData->modules);

            DB::commit();
            return ApiResponseClass::successResponse(new SubscriptionPlanResource($plan));
        }catch (\Exception $e){
            DB::rollBack();
            return ApiResponseClass::errorMsgResponse($e->getMessage());
        }

    }


    public function update(UpdateSubscriptionPlanRequest $request,$id){
        try{
            DB::beginTransaction();

            $plan = SubscriptionPlan::findOrFail($id);
            $planData = SubscriptionPlanData::fromRequest($request,$plan);
            $plan->update($planData->initializeForUpdate($planData));
            SubscriptionPlanServices::updateMoreThanModuleInPlan($plan,$planData->modules);

            DB::commit();
            return ApiResponseClass::successResponse(new SubscriptionPlanResource($plan));
        }catch (\Exception $e){
            DB::rollBack();
            return ApiResponseClass::errorMsgResponse($e->getMessage());
        }
    }

    public function destroy(Request $request,$id){
        $plan = SubscriptionPlan::findOrFail($id);
//        $plan->deleted = 1;
        $plan->delete();
        return ApiResponseClass::deletedResponse();
    }

}
