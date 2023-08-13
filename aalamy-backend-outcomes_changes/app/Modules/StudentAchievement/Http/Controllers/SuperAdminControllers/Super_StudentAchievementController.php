<?php

namespace Modules\StudentAchievement\Http\Controllers\SuperAdminControllers;

use App\Http\Controllers\Classes\ApiResponseClass;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Modules\StudentAchievement\Http\Resources\StudentAchievementResource;
use Modules\StudentAchievement\Models\StudentAchievement;

class Super_StudentAchievementController extends Controller
{


    public function paginate(Request $request,$soft_delete=null){
        $studentAchievements = StudentAchievement::search($request->key,[],['Student.User','User'])
            ->trashed($soft_delete)
            ->with(['Student.User','User'=>function($query){
                return $query->with(['Educator','School','Parent']);
            }])
            ->paginate(config('panel.admin_paginate_num'));
        return ApiResponseClass::successResponse(StudentAchievementResource::collection($studentAchievements));
    }


    public function softDeleteOrRestore(Request $request,$student_achievement_id){
        DB::beginTransaction();
        $studentAchievement = StudentAchievement::withDeletedItems()
            ->findOrFail($student_achievement_id);
        $studentAchievement->softDeleteOrRestore();
        DB::commit();
        return ApiResponseClass::successResponse(new StudentAchievementResource($studentAchievement));

    }

    public function destroy(Request $request,$student_achievement_id){
        DB::beginTransaction();
        $studentAchievement = StudentAchievement::withDeletedItems()
            ->findOrFail($student_achievement_id);
        $studentAchievement->delete();
        DB::commit();
        return ApiResponseClass::deletedResponse();

    }

}
