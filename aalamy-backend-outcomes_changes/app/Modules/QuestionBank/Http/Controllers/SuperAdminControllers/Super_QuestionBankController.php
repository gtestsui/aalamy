<?php

namespace Modules\QuestionBank\Http\Controllers\SuperAdminControllers;

use App\Http\Controllers\Classes\ApiResponseClass;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Modules\DiscussionCorner\Models\DiscussionCornerPost;
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

class Super_QuestionBankController extends Controller
{

    public function paginate(GetQuestionBankRequest $request,$soft_delete=null){
        $meetings = QuestionBank::search($request->key,[],[
                'Teacher.User',
                'School',
                'Educator.User',
                'Unit',
                'Lesson',
                'LevelSubject'=>['Level','Subject']
            ])
            ->filterMyQuestionBank($request->filter)
            ->trashed($soft_delete)
            ->with([
                'TeacherEvenItsDeleted.UserEvenItsDeleted',
                'SchoolEvenItsDeleted',
                'EducatorEvenItsDeleted.UserEvenItsDeleted',
                'UnitEvenItsDeleted',
                'LessonEvenItsDeleted',
                'LevelSubjectEvenItsDeleted.LevelEvenItsDeleted',
                'LevelSubjectEvenItsDeleted.SubjectEvenItsDeleted',
            ])
            ->paginate(config('panel.admin_paginate_num'));

        return ApiResponseClass::successResponse(QuestionBankResource::collection($meetings));
    }

    public function getQuestionDetails(Request $request,$question_id){

        $questionBank = QuestionBank::findOrFail($question_id);
        $questionClassByType = QuestionManagementFactory::create($questionBank->question_type);
        $questionBank = $questionClassByType->load($questionBank)
            ->load(['LevelSubject'=>function($query){
                return $query->with(['Level','Subject']);
            },'Unit','Lesson']);
        return ApiResponseClass::successResponse(new QuestionBankResource($questionBank));

    }

    public function getEducatorQuestionsBankPaginate(GetQuestionBankRequest $request,$educator_id,$soft_delete=null){
        $user = $request->user();
        $educator = Educator::findOrFail($educator_id);
        $educatorClass = new EducatorQuestionManagement($educator);
        $questionsBank = $educatorClass
            ->setTrashed($soft_delete)
            ->getMyQuestionBankPaginateForAdmin($request->filter);
        return ApiResponseClass::successResponse(QuestionBankResource::collection($questionsBank));

    }

    public function getSchoolQuestionsBankPaginate(GetQuestionBankRequest $request,$school_id,$soft_delete=null){
        $user = $request->user();
        $school = School::findOrFail($school_id);
        $schoolClass = new SchoolQuestionManagement($school);
        $questionsBank = $schoolClass
            ->setTrashed($soft_delete)
            ->getMyQuestionBankPaginateForAdmin($request->filter);
        return ApiResponseClass::successResponse(QuestionBankResource::collection($questionsBank));

    }


    public function softDeleteOrRestore(Request $request,$question_bank_id){
        DB::beginTransaction();
        $questionBank = QuestionBank::withDeletedItems()
            ->findOrFail($question_bank_id);
        $questionBank->softDeleteOrRestore();
        DB::commit();
        return ApiResponseClass::successResponse(new QuestionBankResource($questionBank));

    }

    public function destroy(Request $request,$question_bank_id){
        DB::beginTransaction();
        $questionBank = QuestionBank::withDeletedItems()
            ->findOrFail($question_bank_id);
        $questionBank->delete();
        DB::commit();
        return ApiResponseClass::deletedResponse();

    }

}
