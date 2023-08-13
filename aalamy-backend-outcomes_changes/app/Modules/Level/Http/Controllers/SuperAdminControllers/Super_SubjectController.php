<?php

namespace Modules\Level\Http\Controllers\SuperAdminControllers;

use App\Http\Controllers\Classes\ApiResponseClass;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Modules\Level\Http\Controllers\Classes\ManageSubject\EducatorSubject;
use Modules\Level\Http\Controllers\Classes\ManageSubject\SchoolSubject;
use Modules\Level\Http\Requests\SuperAdminRequests\Subject\GetSubjectsByUserRequest;
use Modules\Level\Http\Resources\SubjectResource;
use Modules\Level\Models\Subject;
use Modules\User\Models\Educator;
use Modules\User\Models\School;

class Super_SubjectController extends Controller
{


    public function paginate(Request $request,$soft_delete=null){
        $user = $request->user();
        DB::enableQueryLog();
        $subjects = Subject::search($request->key,[],['User'])
            ->trashed($soft_delete)
            ->with(['User'=>function($query){
                return $query->with('Educator','School');
            }])
            ->paginate(config('panel.admin_paginate_num'));
//        dd(DB::getQueryLog());
        return ApiResponseClass::successResponse(SubjectResource::collection($subjects));
    }

    public function getElementByIdEvenItsDeleted(Request $request,$id,$soft_delete){
        $subject = Subject::with(['User'=>function($query){
                return $query->with('Educator','School');
            }])
            ->findOrFail($id);

        return ApiResponseClass::successResponse(new SubjectResource($subject));

    }

    public function getEducatorSubjects(GetSubjectsByUserRequest $request,$educator_id,$soft_delete=null){
        $user = $request->user();
        $educator = Educator::findOrFail($educator_id);
        $educatorSubjectClass = new EducatorSubject($educator);
//        $subjects = $educatorSubjectClass->mySubjects();
        $subjects = $educatorSubjectClass->mySubjectsQuery()
            ->trashed($soft_delete)
            ->search($request->key)
            ->get();
        return ApiResponseClass::successResponse(SubjectResource::collection($subjects));

    }

    public function getSchoolSubjects(GetSubjectsByUserRequest $request,$school_id,$soft_delete=null){
        $user = $request->user();
        $school = School::findOrFail($school_id);
        $schoolSubjectClass = new SchoolSubject($school);
//        $subjects = $schoolSubjectClass->mySubjects();
        $subjects = $schoolSubjectClass->mySubjectsQuery()
            ->trashed($soft_delete)
            ->search($request->key)
            ->get();
        return ApiResponseClass::successResponse(SubjectResource::collection($subjects));

    }

    public function softDeleteOrRestore(Request $request,$subject_id){
        DB::beginTransaction();
        $subject = Subject::withDeletedItems()
            ->findOrFail($subject_id);
        $subject->softDeleteOrRestore();
        DB::commit();
        return ApiResponseClass::successResponse($subject);

    }

    public function destroy(Request $request,$subject_id){
        $subject = Subject::withDeletedItems()
            ->findOrFail($subject_id);
        $subject->delete();
        return ApiResponseClass::deletedResponse();

    }



}
