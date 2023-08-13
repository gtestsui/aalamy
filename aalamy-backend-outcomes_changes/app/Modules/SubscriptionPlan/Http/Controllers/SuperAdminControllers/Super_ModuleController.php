<?php

namespace Modules\SubscriptionPlan\Http\Controllers\SuperAdminControllers;

use App\Http\Controllers\Classes\ApiResponseClass;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Modules\SubscriptionPlan\Http\DTO\ModuleData;
use Modules\SubscriptionPlan\Http\Requests\Module\StoreModuleRequest;
use Modules\SubscriptionPlan\Http\Requests\Module\UpdateModuleRequest;
use Modules\SubscriptionPlan\Http\Resources\ModuleResource;
use Modules\SubscriptionPlan\Models\Module;

class Super_ModuleController extends Controller
{
    //dd(trans('user::messages.QuestionTypes'));

    public function index(){
        $modules = Module::active()->get();
        return ApiResponseClass::successResponse(ModuleResource::collection($modules));
    }

    public function getModuleByOwnerType($type){
        $modules = Module::where('type',$type)->active()->get();
        return ApiResponseClass::successResponse(ModuleResource::collection($modules));
    }


    public function store(StoreModuleRequest $request){
        $moduleData = ModuleData::fromRequest($request);
        $module = Module::create($moduleData->all());
        return ApiResponseClass::successResponse(new ModuleResource($module));
    }

    public function update(UpdateModuleRequest $request,$id){
        $moduleData = ModuleData::fromRequest($request);
        $module = Module::findOrFail($id);
        $module->update($moduleData->initializeForUpdate($moduleData));
        return ApiResponseClass::successResponse(new ModuleResource($module));
    }

    public function destroy(Request $request,$id){
        $module = Module::findOrFail($id);
        $module->deleted = 1;
        $module->save();
        return ApiResponseClass::deletedResponse();
    }

}
