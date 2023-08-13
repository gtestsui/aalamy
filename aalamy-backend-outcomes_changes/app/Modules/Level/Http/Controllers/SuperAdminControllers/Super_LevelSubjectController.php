<?php

namespace Modules\Level\Http\Controllers\SuperAdminControllers;

use App\Http\Controllers\Classes\ApiResponseClass;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Modules\Level\Http\Resources\LevelSubjectResource;
use Modules\Level\Models\LevelSubject;

class Super_LevelSubjectController extends Controller
{


    public function paginateWithFilter(Request $request,$soft_delete=null){
        $user = $request->user();
        DB::connection()->enableQueryLog();
        $levelSubjects = LevelSubject::search($request->key,[],['Level','Subject'])
            ->trashed($soft_delete)
            ->filterBy([
                'level_id' => $request->by_level_id,
                'subject_id' => $request->by_subject_id,
            ])
            ->with(['Level','Subject'])
            ->paginate(config('panel.admin_paginate_num'));
//        dd(DB::getQueryLog());
        return ApiResponseClass::successResponse(LevelSubjectResource::collection($levelSubjects));
    }

    public function getByLevelId(Request $request,$level_id){
        $levelSubjects = LevelSubject::query()
            ->filterBy([
                'level_id' => $level_id,
            ])
            ->with('Subject')
            ->get();

        return ApiResponseClass::successResponse(LevelSubjectResource::collection($levelSubjects));

    }

    public function softDeleteOrRestore(Request $request,$level_subject_id){
        DB::beginTransaction();
        $levelSubject = LevelSubject::withDeletedItems()
            ->findOrFail($level_subject_id);
        $levelSubject->softDeleteOrRestore();
        DB::commit();
        return ApiResponseClass::successResponse($levelSubject);

    }

    public function destroy(Request $request,$level_subject_id){
        $levelSubject = LevelSubject::withDeletedItems()
            ->findOrFail($level_subject_id);
        $levelSubject->delete();
        return ApiResponseClass::deletedResponse();

    }



}
