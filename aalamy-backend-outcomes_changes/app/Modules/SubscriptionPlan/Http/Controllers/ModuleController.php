<?php

namespace Modules\SubscriptionPlan\Http\Controllers;

use App\Http\Controllers\Controller;

class ModuleController extends Controller
{
    //dd(trans('user::messages.QuestionTypes'));

//    public function index(){
//        $modules = Module::active()->get();
//        return ApiResponseClass::successResponse(ModuleResource::collection($modules));
//    }
//
//
//    public function store(StoreModuleRequest $request){
//        $moduleData = ModuleData::fromRequest($request);
//        $module = Module::create($moduleData->all());
//        return ApiResponseClass::successResponse(new ModuleResource($module));
//    }
//
//    public function update(UpdateModuleRequest $request,$id){
//        $moduleData = ModuleData::fromRequest($request);
//        $module = Module::findOrFail($id);
//        $module->update($moduleData->initializeForUpdate($moduleData));
//        return ApiResponseClass::successResponse(new ModuleResource($module));
//    }
//
//    public function destroy(Request $request,$id){
//        $module = Module::findOrFail($id);
//        $module->deleted = 1;
//        $module->save();
//        return ApiResponseClass::deletedResponse();
//    }

}
