<?php

namespace Modules\Level\Http\Controllers;

use App\Http\Controllers\Classes\ApiResponseClass;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Modules\Level\Http\Controllers\Classes\LevelServices;
use Modules\Level\Http\Controllers\Classes\ManageLevel\LevelManagementFactory;
use Modules\Level\Http\DTO\LevelData;
use Modules\Level\Http\Requests\Level\DestroyLevelRequest;
use Modules\Level\Http\Requests\Level\GetMyLevelsRequest;
use Modules\Level\Http\Requests\Level\StoreLevelRequest;
use Modules\Level\Http\Requests\Level\UpdateLevelRequest;
use Modules\Level\Http\Resources\LevelResource;
use Modules\Level\Models\Level;

class LevelController extends Controller
{

    public function index(){
        $levels = Level::with(['User'=>function($query){
            return $query->with('Educator','School');
        }])->get();
        return ApiResponseClass::successResponse(LevelResource::collection($levels));
    }

    public function myLevels(GetMyLevelsRequest $request){
        $user = $request->user();
//        $manageLevelClass = LevelServices::createManageLevelClassByType($user->account_type,$user,$request->my_teacher_id);
        $manageLevelClass = LevelManagementFactory::create($user,$request->my_teacher_id);
        $myLevels = $manageLevelClass->myLevels();
        return ApiResponseClass::successResponse(LevelResource::collection($myLevels));

    }

    public function store(StoreLevelRequest $request){
        $user = $request->user();
        $levelData = LevelData::fromRequest($request,$user);
        $level = Level::create($levelData->all());
        return ApiResponseClass::successResponse(new LevelResource($level));
    }

    public function update(UpdateLevelRequest $request,$id){
        $user = $request->user();
        $level = $request->getLevel();
        $levelData = LevelData::fromRequest($request,$user);
        $level->update($levelData->initializeForUpdate($levelData));
        return ApiResponseClass::successResponse(new LevelResource($level));
    }

    public function softDelete(DestroyLevelRequest $request,$id){
        DB::beginTransaction();
        $level = $request->getLevel();
        $level->softDeleteObject();
        DB::commit();
        return ApiResponseClass::deletedResponse();
    }

    public function destroy(DestroyLevelRequest $request,$id){
        $level = $request->getLevel();
        $level->delete();
        return ApiResponseClass::deletedResponse();
    }



}
