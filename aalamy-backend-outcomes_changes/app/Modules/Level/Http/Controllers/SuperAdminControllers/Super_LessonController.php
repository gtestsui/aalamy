<?php

namespace Modules\Level\Http\Controllers\SuperAdminControllers;

use App\Http\Controllers\Classes\ApiResponseClass;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Modules\Level\Http\Resources\LessonResource;
use Modules\Level\Models\Lesson;

class Super_LessonController extends Controller
{


    public function paginate(Request $request,$soft_delete=null){
        $user = $request->user();
        $lessons = Lesson::search($request->key,[],[
                'Unit'/*=>['Subject','Level']*/,
                'User'
            ])
            ->trashed($soft_delete)
            ->with(['User'=>function($query){
                return $query->with('Educator','School');
            },'Unit'])
            ->paginate(config('panel.admin_paginate_num'));
        return ApiResponseClass::successResponse(LessonResource::collection($lessons));
    }

    public function getElementByIdEvenItsDeleted(Request $request,$id,$soft_delete){
        $lesson = Lesson::with(['User'=>function($query){
                return $query->with('Educator','School');
            },'Unit'])
            ->findOrFail($id);

        return ApiResponseClass::successResponse(new LessonResource($lesson));

    }


    public function getByUnitId(Request $request,$unit_id){
        $lessons = Lesson::where('unit_id',$unit_id)
            ->get();
        return ApiResponseClass::successResponse(LessonResource::collection($lessons));
    }

    public function softDeleteOrRestore(Request $request,$lesson_id){
        DB::beginTransaction();
        $lesson = Lesson::withDeletedItems()
            ->findOrFail($lesson_id);
        $lesson->softDeleteOrRestore();
        DB::commit();
        return ApiResponseClass::successResponse($lesson);

    }


    public function destroy(Request $request,$lesson_id){
        $lesson = Lesson::withDeletedItems()
            ->findOrFail($lesson_id);
        $lesson->delete();
        return ApiResponseClass::deletedResponse();

    }




}
