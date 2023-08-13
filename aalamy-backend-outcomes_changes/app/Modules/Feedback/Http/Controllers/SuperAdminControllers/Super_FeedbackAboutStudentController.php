<?php

namespace Modules\Feedback\Http\Controllers\SuperAdminControllers;

use App\Http\Controllers\Classes\ApiResponseClass;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Modules\Feedback\Http\Controllers\Classes\FeedbackServices;
use Modules\Feedback\Http\Controllers\Classes\ManageFiles\FeedbackAboutStudentAttachmentClass;
use Modules\Feedback\Http\DTO\FeedbackAboutStudentData;
use Modules\Feedback\Http\Requests\FeedbackAboutStudent\DestroyFeedbackAboutStudentRequest;
use Modules\Feedback\Http\Requests\FeedbackAboutStudent\GetMyChildesFeedbackAboutStudentRequest;
use Modules\Feedback\Http\Requests\FeedbackAboutStudent\GetMyFeedbackAboutStudentRequest;
use Modules\Feedback\Http\Requests\FeedbackAboutStudent\StoreFeedbackAboutStudentRequest;
use Modules\Feedback\Http\Requests\FeedbackAboutStudent\UpdateFeedbackAboutStudentRequest;
use Modules\Feedback\Http\Resources\FeedbackAboutStudentResource;
use Modules\Feedback\Models\FeedbackAboutStudent;
use Modules\User\Http\Controllers\Classes\ManageStudent\StudentParentClass;

class Super_FeedbackAboutStudentController extends Controller
{

    public function paginate(Request $request,$soft_delete=null){
        $feedbacks = FeedbackAboutStudent::search($request->key,[],[
                'School','Student.User','Educator.User','Teacher.User'
            ])
            ->with([
                'School.User','Student.User','Educator.User','Teacher.User'
            ])
            ->trashed($soft_delete)
            ->paginate(config('panel.admin_paginate_num'));
        return ApiResponseClass::successResponse(FeedbackAboutStudentResource::collection($feedbacks));
    }

    public function softDeleteOrRestore(Request $request,$feedback_id){
        DB::beginTransaction();
        $feedback = FeedbackAboutStudent::withDeletedItems()
            ->findOrFail($feedback_id);
        $feedback->softDeleteOrRestore();
        DB::commit();
        return ApiResponseClass::successResponse(new FeedbackAboutStudentResource($feedback));

    }

    public function destroy(Request $request,$feedback_id){
        DB::beginTransaction();
        $feedback = FeedbackAboutStudent::withDeletedItems()
            ->findOrFail($feedback_id);
        $feedback->delete();
        DB::commit();
        return ApiResponseClass::deletedResponse();

    }

}
