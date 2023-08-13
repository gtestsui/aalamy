<?php

namespace Modules\Feedback\Http\Controllers;

use App\Http\Controllers\Classes\ApiResponseClass;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Modules\Feedback\Http\Controllers\Classes\FeedbackServices;
use Modules\Feedback\Http\Controllers\Classes\ManageFeedback\FeedbackAboutStudentManagementFactory;
use Modules\Feedback\Http\Controllers\Classes\ManageFiles\FeedbackAboutStudentAttachmentClass;
use Modules\Feedback\Http\DTO\FeedbackAboutStudentData;
use Modules\Feedback\Http\Requests\FeedbackAboutStudent\DestroyFeedbackAboutStudentRequest;
use Modules\Feedback\Http\Requests\FeedbackAboutStudent\GetMyChildesFeedbackAboutStudentRequest;
use Modules\Feedback\Http\Requests\FeedbackAboutStudent\GetMyFeedbackAboutStudentRequest;
use Modules\Feedback\Http\Requests\FeedbackAboutStudent\StoreFeedbackAboutStudentRequest;
use Modules\Feedback\Http\Requests\FeedbackAboutStudent\UpdateFeedbackAboutStudentRequest;
use Modules\Feedback\Http\Resources\FeedbackAboutStudentResource;
use Modules\Feedback\Models\FeedbackAboutStudent;
use Modules\Feedback\Observers\FeedbackAboutStudentObserver;
use Modules\User\Http\Controllers\Classes\ManageStudent\StudentParentClass;

class FeedbackAboutStudentController extends Controller
{

    /**
     * get feedback added by me for educator and school
     */
    public function getMyFeedbackPaginate(GetMyFeedbackAboutStudentRequest $request){
        $user = $request->user();
        $manageFeedbackClass = FeedbackAboutStudentManagementFactory::create(
            $user,$request->my_teacher_id
        );
        $myFeedback = $manageFeedbackClass->getMyFeedbackPaginate();
        return ApiResponseClass::successResponse(FeedbackAboutStudentResource::collection($myFeedback));
    }


    public function getMyChildesFeedback(GetMyChildesFeedbackAboutStudentRequest $request){
        $user = $request->user();
        $parent = $user->Parent;
        $studentParentClass = new StudentParentClass($parent);
        $myStudentIds = $studentParentClass->myStudentIds();
        $myChildesFeedback = FeedbackAboutStudent::whereIn('student_id',$myStudentIds)
            ->withAllRelations()
            ->sharedStatus(true)
            ->paginate(10);
        return ApiResponseClass::successResponse(FeedbackAboutStudentResource::collection($myChildesFeedback));

    }

    /**
     * @see FeedbackAboutStudentObserver for notification
     * we had used observer to publish notification if the share_with_parent
     * in request is sent true
     */
    public function store(StoreFeedbackAboutStudentRequest $request){
        $user = $request->user();
        DB::beginTransaction();
        $feedbackData = FeedbackAboutStudentData::fromRequest($request);
        $feedback = FeedbackAboutStudent::create($feedbackData->all());
        $feedbackAttachment = new FeedbackAboutStudentAttachmentClass($feedback,$feedbackData);
        $feedbackAttachment->addImageToFeedback();
        $feedbackAttachment->addFileToFeedback();
        $feedbackAttachment->addAttendanceToFeedback($user);
        $feedbackAttachment->addMarksToFeedback($user);

        DB::commit();
        return ApiResponseClass::successResponse(new FeedbackAboutStudentResource($feedback));
    }
    /**
     * we had used observer to publish notification if the share_with_parent
     * updated and is true
     */
    public function update(UpdateFeedbackAboutStudentRequest $request,$id){
        $user = $request->user();
        $feedback = $request->getFeedback();
        $feedbackData = FeedbackAboutStudentData::fromRequest($request,true);
        $feedback->share_with_parent = 0;
        $feedback->update($feedbackData->initializeForUpdate($feedbackData));
        return ApiResponseClass::successResponse(new FeedbackAboutStudentResource($feedback));
    }

    public function destroy(DestroyFeedbackAboutStudentRequest $request,$id){
        $user = $request->user();
        $feedback = $request->getFeedback();
        $feedback->delete();
        return ApiResponseClass::deletedResponse();
    }


}
