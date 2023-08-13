<?php

namespace Modules\Level\Http\Controllers;

use App\Http\Controllers\Classes\ApiResponseClass;
use App\Http\Controllers\Classes\RequestServicesClass;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Modules\Level\Http\Controllers\Classes\LevelServices;
use Modules\Level\Http\Controllers\Classes\ManageUnit\UnitManagementFactory;
use Modules\Level\Http\DTO\UnitData;
use Modules\Level\Http\Requests\Unit\DestroyUnitRequest;
use Modules\Level\Http\Requests\Unit\GetMyUnitsByLevelSubjectIdRequest;
use Modules\Level\Http\Requests\Unit\GetMyUnitsPaginateRequest;
use Modules\Level\Http\Requests\Unit\StoreUnitRequest;
use Modules\Level\Http\Requests\Unit\UpdateUnitRequest;
use Modules\Level\Http\Resources\UnitResource;
use Modules\Level\Models\Unit;

class UnitController extends Controller
{

    public function getMyUnitsAll(GetMyUnitsPaginateRequest $request){
        $user = $request->user();
        $manageClass = UnitManagementFactory::create($user,$request->my_teacher_id);
//        $manageClass = LevelServices::createManageLevelClassByType($user->account_type,$user,$request->my_teacher_id);
        $myUnits = $manageClass->myUnitsAllWithLevelSubject($request->level_subject_id);
        return ApiResponseClass::successResponse(UnitResource::collection($myUnits));

    }

    public function getMyUnitsPaginate(GetMyUnitsPaginateRequest $request){
        $user = $request->user();
        $manageClass = UnitManagementFactory::create($user,$request->my_teacher_id);
//        $manageClass = LevelServices::createManageLevelClassByType($user->account_type,$user,$request->my_teacher_id);
        $myUnits = $manageClass->myUnitsPaginateWithFilter($request->level_subject_id);
        return ApiResponseClass::successResponse(UnitResource::collection($myUnits));

    }

    public function getAllMyUnitsByLevelSubjectId(GetMyUnitsByLevelSubjectIdRequest $request,$level_subject_id){
        $myUnits = Unit::withLevelSubjectInfo()
            ->where('level_subject_id',$level_subject_id)
            ->get();
        return ApiResponseClass::successResponse(UnitResource::collection($myUnits));
    }

    public function store(StoreUnitRequest $request){
        $user = $request->user();
        $unitData = UnitData::fromRequest($request,$user);
        $unit = Unit::create($unitData->all());
        $unit->loadLevelSubjectInfo();
        return ApiResponseClass::successResponse(new UnitResource($unit));

    }

    public function update(UpdateUnitRequest $request,$id){
        $user = $request->user();
        $unit = $request->getUnit();
        $unitData = UnitData::fromRequest($request,$user,1);
        $unit->update($unitData->initializeForUpdate($unitData));
        $unit->loadLevelSubjectInfo();
        return ApiResponseClass::successResponse(new UnitResource($unit));
    }

    public function softDelete(DestroyUnitRequest $request,$id){
        DB::beginTransaction();
        $unit = $request->getUnit();
        $unit->softDeleteObject();
        DB::commit();
        return ApiResponseClass::deletedResponse();
    }

    public function destroy(DestroyUnitRequest $request,$id){
        $unit = $request->getUnit();
        $unit->delete();
        return ApiResponseClass::deletedResponse();
    }



}
