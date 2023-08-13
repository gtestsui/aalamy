<?php

namespace Modules\Level\Http\Controllers\SuperAdminControllers;

use App\Http\Controllers\Classes\ApiResponseClass;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Modules\Level\Http\Controllers\Classes\LevelServices;
use Modules\Level\Http\Controllers\Classes\ManageLevel\EducatorLevel;
use Modules\Level\Http\Controllers\Classes\ManageLevel\LevelManagementFactory;
use Modules\Level\Http\Controllers\Classes\ManageLevel\SchoolLevel;
use Modules\Level\Http\Requests\SuperAdminRequests\Level\GetLevelsByUserRequest;
use Modules\Level\Http\Resources\LevelResource;
use Modules\Level\Models\Level;
use Modules\User\Models\Educator;
use Modules\User\Models\School;
use Modules\User\Models\User;

class Super_LevelController extends Controller
{


    public function paginate(Request $request,$soft_delete=null){
        $user = $request->user();
        $levels = Level::search($request->key,[],['User'])
        ->trashed($soft_delete)
        ->with(['User'=>function($query){
            return $query->withDeletedItems()
                ->with('Educator','School');
        }])->paginate(config('panel.admin_paginate_num'));
        return ApiResponseClass::successResponse(LevelResource::collection($levels));
    }

    public function getElementByIdEvenItsDeleted(Request $request,$id,$soft_delete){
        $level = Level::with(['User'=>function($query){
                return $query->with('Educator','School');
            }])->findOrFail($id);

        return ApiResponseClass::successResponse(new LevelResource($level));

    }


    public function getEducatorLevels(Request $request,$educator_id,$soft_delete=null){
        $user = $request->user();
        $educator = Educator::findOrFail($educator_id);
        $educatorClass = new EducatorLevel($educator);
//        $levels = $educatorClass->myLevels();
        $levels = $educatorClass->myLevelsQuery()
            ->trashed($soft_delete)
            ->search($request->key)
            ->get();
        return ApiResponseClass::successResponse(LevelResource::collection($levels));

    }

    public function getSchoolLevels(Request $request,$school_id,$soft_delete=null){
        $user = $request->user();
        $school = School::findOrFail($school_id);
        $schoolClass = new SchoolLevel($school);
//        $levels = $schoolClass->myLevels();
        $levels = $schoolClass->myLevelsQuery()
            ->trashed($soft_delete)
            ->search($request->key)
            ->get();
        return ApiResponseClass::successResponse(LevelResource::collection($levels));

    }


    public function getLevels(GetLevelsByUserRequest $request,$user_id){
        $user = $request->user();
        $targetUser = User::findOrFail($user_id);
//        $manageLevelClass = LevelServices::createManageLevelClassByType($targetUser->account_type,$targetUser,$request->teacher_id);
        $manageLevelClass = LevelManagementFactory::create($targetUser,$request->teacher_id);
        $userLevels = $manageLevelClass->myLevels();
        return ApiResponseClass::successResponse(LevelResource::collection($userLevels));

    }


    public function softDeleteOrRestore(Request $request,$level_id){
        DB::beginTransaction();
        $level = Level::withDeletedItems()
            ->findOrFail($level_id);
        $level->softDeleteOrRestore();
        DB::commit();
        return ApiResponseClass::successResponse($level);

    }


    public function destroy(Request $request,$level_id){
        $level = Level::withDeletedItems()
            ->findOrFail($level_id);
        $level->delete();
        return ApiResponseClass::deletedResponse();

    }


}
