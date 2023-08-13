<?php

namespace Modules\LearningResource\Http\Controllers\SuperAdminControllers;

use App\Http\Controllers\Classes\ApiResponseClass;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Modules\DiscussionCorner\Models\DiscussionCornerPost;
use Modules\LearningResource\Http\Resources\LearningResourceResource;
use Modules\LearningResource\Models\LearningResource;
use Modules\Meeting\Http\Resources\SuperAdminResources\SuperAdminMeetingResource;
use Modules\Meeting\Models\Meeting;
use Modules\QuestionBank\Http\Controllers\Classes\ManageQuestion\QuestionManagementFactory;
use Modules\QuestionBank\Http\Controllers\Classes\ManageQuestionByAccountType\EducatorQuestionManagement;
use Modules\QuestionBank\Http\Controllers\Classes\ManageQuestionByAccountType\SchoolQuestionManagement;
use Modules\QuestionBank\Http\Requests\SuperAdmin\GetQuestionBankRequest;
use Modules\QuestionBank\Http\Resources\QuestionBank\QuestionBankResource;
use Modules\QuestionBank\Models\QuestionBank;
use Modules\User\Models\Educator;
use Modules\User\Models\School;

class Super_LearningResourceController extends Controller
{

    public function paginate(Request $request,$soft_delete=null){
        $meetings = LearningResource::search($request->key,[],[
                'Teacher.User',
                'School',
                'Educator.User',
                'Topic',
                'Unit',
                'Lesson',
                'LevelSubject'=>['Level','Subject']
            ])
            ->trashed($soft_delete)
            ->with([
                'Teacher.User',
                'School',
                'Educator.User',
                'Unit',
                'Lesson',
                'LevelSubject.Level',
                'LevelSubject.Subject',
                'Topic',
            ])
            ->paginate(config('panel.admin_paginate_num'));

        return ApiResponseClass::successResponse(LearningResourceResource::collection($meetings));
    }



//    public function getEducatorQuestionsBankPaginate(GetQuestionBankRequest $request,$educator_id,$soft_delete=null){
//        $user = $request->user();
//        $educator = Educator::findOrFail($educator_id);
//        $educatorClass = new EducatorQuestionManagement($educator);
//        $questionsBank = $educatorClass
//            ->setTrashed($soft_delete)
//            ->getMyQuestionBankPaginateForAdmin($request->filter);
//        return ApiResponseClass::successResponse(QuestionBankResource::collection($questionsBank));
//
//    }
//
//    public function getSchoolQuestionsBankPaginate(GetQuestionBankRequest $request,$school_id,$soft_delete=null){
//        $user = $request->user();
//        $school = School::findOrFail($school_id);
//        $schoolClass = new SchoolQuestionManagement($school);
//        $questionsBank = $schoolClass
//            ->setTrashed($soft_delete)
//            ->getMyQuestionBankPaginateForAdmin($request->filter);
//        return ApiResponseClass::successResponse(QuestionBankResource::collection($questionsBank));
//
//    }


    public function softDeleteOrRestore(Request $request,$learning_resource_id){
        DB::beginTransaction();
        $learningResource = LearningResource::withDeletedItems()
            ->findOrFail($learning_resource_id);
        $learningResource->softDeleteOrRestore();
        DB::commit();
        return ApiResponseClass::successResponse(new LearningResourceResource($learningResource));

    }

    public function destroy(Request $request,$learning_resource_id){
        DB::beginTransaction();
        $learningResource = LearningResource::withDeletedItems()
            ->findOrFail($learning_resource_id);
        $learningResource->delete();
        DB::commit();
        return ApiResponseClass::deletedResponse();

    }

}
