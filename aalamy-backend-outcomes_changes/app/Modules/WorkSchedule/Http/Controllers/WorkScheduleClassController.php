<?php

namespace Modules\WorkSchedule\Http\Controllers;

use App\Http\Controllers\Classes\ApiResponseClass;
use App\Http\Controllers\Controller;
use Modules\WorkSchedule\Http\Controllers\Classes\ManageWorkSchedule\WorkScheduleManagementFactory;
use Modules\WorkSchedule\Http\DTO\WorkScheduleClassData;
use Modules\WorkSchedule\Http\Requests\WorkScheduleClass\DestroyWorkScheduleClassRequest;
use Modules\WorkSchedule\Http\Requests\WorkScheduleClass\GetWorkScheduleClassByClassIdForCreatorRequest;
use Modules\WorkSchedule\Http\Requests\WorkScheduleClass\GetWorkScheduleForReaderRequest;
use Modules\WorkSchedule\Http\Requests\WorkScheduleClass\StoreWorkScheduleClassRequest;
use Modules\WorkSchedule\Http\Requests\WorkScheduleClass\UpdateWorkScheduleClassRequest;
use Modules\WorkSchedule\Http\Resources\WorkScheduleClassResource;
use Modules\WorkSchedule\Models\WorkScheduleClass;

class WorkScheduleClassController extends Controller
{


    public function getWorkScheduleForReader(GetWorkScheduleForReaderRequest $request){
        $user = $request->user();
        $workScheduleManagment = WorkScheduleManagementFactory::createForReader($user);
        $workScheduleClasses = $workScheduleManagment->myWorkSchedule();

        return ApiResponseClass::successResponse(WorkScheduleClassResource::collection($workScheduleClasses));

    }


    public function getByClassIdForCreator(GetWorkScheduleClassByClassIdForCreatorRequest $request,$classId){

        $user = $request->user();
        $workScheduleManagment = WorkScheduleManagementFactory::createForCreator($user);
        $workScheduleClasses = $workScheduleManagment->getByClassId($classId);

        return ApiResponseClass::successResponse(WorkScheduleClassResource::collection($workScheduleClasses));

    }


    /**
     * @see WorkSchedule\config\WorkScheduleConfig
     * @param StoreWorkScheduleClassRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function storeOrUpdate(StoreWorkScheduleClassRequest $request,$class_id){
        $workScheduleClassData = WorkScheduleClassData::fromRequest($request);
        $workScheduleClass = WorkScheduleClass::where('class_id',$workScheduleClassData->class_id)
            ->where('week_day_id',$workScheduleClassData->week_day_id)
            ->where('period_number',$workScheduleClassData->period_number)
            ->first();
        if(is_null($workScheduleClass)){
            $workScheduleClass = WorkScheduleClass::create($workScheduleClassData->all());
        }else{
            if($workScheduleClassData->delete_it){
                $workScheduleClass->delete();
            }else{
                $workScheduleClass->update($workScheduleClassData->initializeForUpdate());
            }
        }

        return ApiResponseClass::successResponse(new WorkScheduleClassResource($workScheduleClass));
    }

    public function update(UpdateWorkScheduleClassRequest $request,$id){
        $workScheduleClass = $request->getWorkScheduleClass();
        $workScheduleClassData = WorkScheduleClassData::fromRequest($request,true);
        $workScheduleClass->update($workScheduleClassData->initializeForUpdate());
        return ApiResponseClass::successResponse(new WorkScheduleClassResource($workScheduleClass));
    }


    public function destroy(DestroyWorkScheduleClassRequest $request,$id){
        $workScheduleClass = $request->getWorkScheduleClass();
        $workScheduleClass->delete();
        return ApiResponseClass::deletedResponse();

    }



}
