<?php

namespace Modules\Level\Http\Controllers;

use App\Http\Controllers\Classes\ApiResponseClass;
use App\Http\Controllers\Classes\RequestServicesClass;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Modules\Level\Http\Controllers\Classes\LevelServices;
use Modules\Level\Http\Controllers\Classes\ManageLevel\LevelManagementFactory;
use Modules\Level\Http\Requests\LevelSubject\DestroyLevelSubjectRequest;
use Modules\Level\Http\Requests\LevelSubject\GetMyLevelSubjectByLevelIdRequest;
use Modules\Level\Http\Requests\LevelSubject\GetMyLevelSubjectsRequest;
use Modules\Level\Http\Requests\LevelSubject\RelateToMoreThanSubjectRequest;
use Modules\Level\Http\Requests\LevelSubject\StoreLevelSubjectRequest;
use Modules\Level\Http\Resources\LevelSubjectResource;
use Modules\Level\Models\LevelSubject;
use Modules\Level\Models\Subject;

class LevelSubjectController extends Controller
{

    public function myLevelSubjectsAll(GetMyLevelSubjectsRequest $request){
        $user = $request->user();
//        $manageLevelClass = LevelServices::createManageLevelClassByType($user->account_type,$user,$request->my_teacher_id);
        $manageLevelClass = LevelManagementFactory::create($user,$request->my_teacher_id);
        $myLevelSubjects = $manageLevelClass->myLevelSubjectsAll();
        return ApiResponseClass::successResponse(LevelSubjectResource::collection($myLevelSubjects));

    }

    public function myLevelSubjectsPaginate(GetMyLevelSubjectsRequest $request){
        $user = $request->user();
//        $manageLevelClass = LevelServices::createManageLevelClassByType($user->account_type,$user,$request->my_teacher_id);
        $manageLevelClass = LevelManagementFactory::create($user,$request->my_teacher_id);
        $myLevelSubjects = $manageLevelClass->myLevelSubjectsPaginate([
            'level_id' => $request->by_level_id,
            'subject_id' => $request->by_subject_id,
        ]);
        return ApiResponseClass::successResponse(LevelSubjectResource::collection($myLevelSubjects));

    }


    public function getByLevelId(GetMyLevelSubjectByLevelIdRequest $request,$levelId){
        $user = $request->user();
        $levelManageClass = LevelManagementFactory::create($user);
        $levelSubjects = $levelManageClass->myLevelSubjectsByLevelId($levelId);
//        $levelSubjects = LevelSubject::with(['Subject'])
//            ->where('level_id',$levelId)
//            ->get();
        return ApiResponseClass::successResponse(LevelSubjectResource::collection($levelSubjects));
    }

    public function relateToMoreThanSubject(RelateToMoreThanSubjectRequest $request){
        $user = $request->user();
        foreach($request->subject_ids as $subjectId){
            $subject = Subject::findOrFail($subjectId);
            LevelServices::checkOwnerSubjectAuthorization($user,$subject);
            $foundRelation = LevelSubject::where('level_id',$request->level_id)
                ->where('subject_id',$subjectId)->first();
            if(is_null($foundRelation))
                LevelSubject::create([
                    'level_id' => $request->level_id,
                    'subject_id' => $subjectId,
                ]);
        }

//        $manageLevelClass = LevelServices::createManageLevelClassByType($user->account_type,$user,$request->my_teacher_id);
        $manageLevelClass = LevelManagementFactory::create($user,$request->my_teacher_id);
        $myLevelSubjects = $manageLevelClass->myLevelSubjectsPaginate();
        return ApiResponseClass::successResponse(LevelSubjectResource::collection($myLevelSubjects));


    }

    public function softDelete(DestroyLevelSubjectRequest $request,$id){
        $levelSubject = $request->getLevelSubject();
        DB::beginTransaction();
        DB::connection()->enableQueryLog();
//        return $levelSubject;
//        LevelSubject::where('id',$id)->softDelete();
//        $levelSubject->softDeleteOrRestore();
        $levelSubject->softDeleteObject();
//        dd($levelSubject);

        DB::commit();
//        dd(DB::getQueryLog());
        return ApiResponseClass::deletedResponse();
    }

    public function destroy(DestroyLevelSubjectRequest $request,$id){
        $levelSubject = $request->getLevelSubject();
        $levelSubject->delete();
        return ApiResponseClass::deletedResponse();
    }



}
