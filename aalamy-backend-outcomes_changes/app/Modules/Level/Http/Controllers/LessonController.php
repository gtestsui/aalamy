<?php

namespace Modules\Level\Http\Controllers;

use App\Http\Controllers\Classes\ApiResponseClass;
use App\Http\Controllers\Classes\RequestServicesClass;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Modules\Level\Http\Controllers\Classes\LevelServices;
use Modules\Level\Http\Controllers\Classes\ManageLesson\LessonManagementFactory;
use Modules\Level\Http\DTO\LessonData;
use Modules\Level\Http\DTO\UnitData;
use Modules\Level\Http\Requests\Lesson\DestroyLessonRequest;
use Modules\Level\Http\Requests\Lesson\GetMyLessonsByUnitIdsRequest;
use Modules\Level\Http\Requests\Lesson\GetMyLessonsRequest;
use Modules\Level\Http\Requests\Lesson\StoreLessonRequest;
use Modules\Level\Http\Requests\Lesson\UpdateLessonRequest;
use Modules\Level\Http\Requests\Lesson\GetMyLessonsByUnitIdRequest;
use Modules\Level\Http\Requests\Unit\StoreUnitRequest;
use Modules\Level\Http\Requests\Unit\UpdateUnitRequest;
use Modules\Level\Http\Resources\LessonResource;
use Modules\Level\Http\Resources\UnitResource;
use Modules\Level\Models\Lesson;
use Modules\Level\Models\Unit;

class LessonController extends Controller
{


    public function getMyLessonsPaginate(GetMyLessonsRequest $request){
        $user = $request->user();
        $manageClass = LessonManagementFactory::create($user,$request->my_teacher_id);
//        $manageLevelClass = LevelServices::createManageLevelClassByType($user->account_type,$user,$request->my_teacher_id);
        $myLessons = $manageClass->myLessonsPaginateWithFilter($request->unit_id);
        return ApiResponseClass::successResponse(LessonResource::collection($myLessons));

    }

    public function getAllMyLesson(Request $request){
        $user = $request->user();
        $manageClass = LessonManagementFactory::create($user,$request->my_teacher_id);
        $myLessons = $manageClass->myLessonsAll($request->unit_id);
        return ApiResponseClass::successResponse(LessonResource::collection($myLessons));

    }

    public function getAllMyLessonsByUnitId(GetMyLessonsByUnitIdRequest $request,$unit_id){
        $lessons = Lesson::where('unit_id',$unit_id)
            /*->with('User')*/
            ->get();
//            ->paginate(10);
        return ApiResponseClass::successResponse(LessonResource::collection($lessons));
    }

    public function getAllMyLessonsByUnitIds(GetMyLessonsByUnitIdsRequest $request){
        $lessons = Lesson::whereIn('unit_id',$request->unit_ids)
            /*->with('User')*/
            ->get();
//            ->paginate(10);
        return ApiResponseClass::successResponse(LessonResource::collection($lessons));
    }

    public function store(StoreLessonRequest $request){
        $user = $request->user();
        $lessonData = LessonData::fromRequest($request,$user);
        $lesson = Lesson::create($lessonData->all());
        $lesson->load(['Unit.LevelSubject'=>function($query){
            return $query->with(['Level','Subject']);
        }]);
        return ApiResponseClass::successResponse(new LessonResource($lesson));

    }

    public function update(UpdateLessonRequest $request,$id){
        $user = $request->user();
        $lesson = $request->getLesson();
        $lessonData = LessonData::fromRequest($request,$user,1);
        $lesson->update($lessonData->initializeForUpdate($lessonData));
        $lesson->load(['Unit.LevelSubject'=>function($query){
            return $query->with(['Level','Subject']);
        }]);
        return ApiResponseClass::successResponse(new LessonResource($lesson));
    }

    public function softDelete(DestroyLessonRequest $request,$id){
        DB::beginTransaction();
        $lesson = $request->getLesson();
        $lesson->softDeleteObject();
        DB::commit();
        return ApiResponseClass::deletedResponse();
    }

    public function destroy(DestroyLessonRequest $request,$id){
        $lesson = $request->getLesson();
        $lesson->delete();
        return ApiResponseClass::deletedResponse();
    }



}
