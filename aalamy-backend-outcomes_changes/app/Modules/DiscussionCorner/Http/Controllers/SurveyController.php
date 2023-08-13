<?php

namespace Modules\DiscussionCorner\Http\Controllers;

use App\Http\Controllers\Classes\ApiResponseClass;
use App\Http\Controllers\Classes\ServicesClass;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Modules\DiscussionCorner\Http\Controllers\Classes\DiscussionCornerServices;
use Modules\DiscussionCorner\Http\Controllers\Classes\ManageDiscussionCorner\ManageByAccountType\DiscussionCornerByAccountTypeManagementFactory;
use Modules\DiscussionCorner\Http\Controllers\Classes\ManageDiscussionCorner\ManageByCornerOwner\DiscussionCornerByOwnerManagementFactory;
use Modules\DiscussionCorner\Http\Controllers\Classes\SurveyAnswerClass;
use Modules\DiscussionCorner\Http\Controllers\Classes\SurveyQuestionClass;
use Modules\DiscussionCorner\Http\DTO\SurveyData;
use Modules\DiscussionCorner\Http\Requests\Survey\ApproveSurveyRequest;
use Modules\DiscussionCorner\Http\Requests\Survey\DestroySurveyRequest;
use Modules\DiscussionCorner\Http\Requests\Survey\GetSurveysByIdRequest;
use Modules\DiscussionCorner\Http\Requests\Survey\GetSurveysByIdWithAnswersRequest;
use Modules\DiscussionCorner\Http\Requests\Survey\GetSurveysByOwnerIdRequest;
use Modules\DiscussionCorner\Http\Requests\Survey\GetSurveysWaitingApproveRequest;
use Modules\DiscussionCorner\Http\Requests\Survey\StoreSurveyRequest;
use Modules\DiscussionCorner\Http\Requests\Survey\UpdateSurveyRequest;
use Modules\DiscussionCorner\Http\Resources\SurveyResource;
use Modules\DiscussionCorner\Models\DiscussionCornerSurvey;
use Modules\DiscussionCorner\Models\DiscussionCornerSurveyUser;
use Modules\Notification\Jobs\DiscussionCorner\SendNewSurveyWaitingApproveNotification;
use Modules\Notification\Jobs\DiscussionCorner\SendSurveyApprovalNotification;

class SurveyController extends Controller
{


    public function getRandomPaginate(Request $request){
        $user = $request->user();
//        $discussionClassByAccountType = DiscussionCornerServices::createManageDiscussionClassByAccountType($user->account_type,$user);
        $discussionClassByAccountType = DiscussionCornerByAccountTypeManagementFactory::create($user);

        $surveys = $discussionClassByAccountType->getRandomSurveysPaginate();
        return ApiResponseClass::successResponse(SurveyResource::CustomCollection($surveys,$user,$user->{ucfirst($user->account_type)}));
    }

    public function getWaitingApprove(GetSurveysWaitingApproveRequest $request){
        $user = $request->user();
//        $discussionClass = DiscussionCornerServices::createManageDiscussionClassByCornerOwner(ucfirst($user->account_type),$user->{ucfirst($user->account_type)});
        $discussionClass = DiscussionCornerByOwnerManagementFactory::create($user->account_type,$user->{ucfirst($user->account_type)});
        $surveys = $discussionClass->getSurveysWaitingApprovePaginate();
        return ApiResponseClass::successResponse(SurveyResource::CustomCollection($surveys,$user,$user->{ucfirst($user->account_type)}));

    }

    public function getMySurveysPaginate(Request $request){
        $user = $request->user();
        $discussionClass = DiscussionCornerByAccountTypeManagementFactory::create($user);
        $surveys = $discussionClass->getMySurveysPaginate();
        return ApiResponseClass::successResponse(SurveyResource::CustomCollection($surveys,$user,$user->{ucfirst($user->account_type)}));

    }

    public function getByOwnerId(GetSurveysByOwnerIdRequest $request){
        $user = $request->user();
        $discussionClass = $request->getDiscussionClass();
        $surveys = $discussionClass->getSurveysPaginate();
        return ApiResponseClass::successResponse(SurveyResource::CustomCollection($surveys,$user,$user->{ucfirst($user->account_type)}));
    }

    public function show(GetSurveysByIdRequest $request,$id){
        $user = $request->user();
        $survey = $request->getSurvey();
        $survey->load(['User','SurveyQuestions'=>function($query){
            return $query->with([
                'Choices'
//                'Choices.SurveyUserAnswers',/*'SurveyUserAnswers'*/
            ]);
        }]);
        return ApiResponseClass::successResponse(new SurveyResource($survey,$user,$user->{ucfirst($user->account_type)}));
    }

    public function showWithAnswers(GetSurveysByIdWithAnswersRequest $request,$id){
        $user = $request->user();
        $survey = $request->getSurvey();
        $survey->load(['SurveyQuestions'=>function($query){
            return $query->with([
                'Choices.LimitedSurveyUserAnswers.SurveyUser.User.School',
                'LimitedSurveyUserAnswers.SurveyUser.User.School'
            ])
            ->withSum('Choices as answered_user_count','counter');
        }]);

        return ApiResponseClass::successResponse(new SurveyResource($survey,$user,$user->{ucfirst($user->account_type)}));
    }

    public function showWithAnswersByUserId(GetSurveysByIdWithAnswersRequest $request,$id,$user_id){
        $surveyUser = DiscussionCornerSurveyUser::where('survey_id',$id)
            ->where('user_id',$user_id)
            ->firstOrFail();

        $survey = DiscussionCornerSurvey::with([
            'SurveyQuestions.Choices.SurveyUserAnswers'=>function($q)use($surveyUser){
                return $q->where('survey_user_id',$surveyUser->id);
            }])
            ->findOrFail($id);
        return ApiResponseClass::successResponse(new SurveyResource($survey));

    }

    public function store(StoreSurveyRequest $request){
        $user = $request->user();
        DB::beginTransaction();
        $surveyData = SurveyData::fromRequest($request);
        $survey = DiscussionCornerSurvey::create($surveyData->all());
        $surveyQuestionClass = new SurveyQuestionClass();
        $surveyQuestionClass->createOrUpdateOrDeleteMultiple($surveyData->questions,$survey->id);

//        DiscussionCornerServices::addMoreThanQuestionToSurvey($survey,$surveyData->questions);
        DB::commit();
        ServicesClass::dispatchJob(new SendNewSurveyWaitingApproveNotification($survey,$user));
        return ApiResponseClass::successResponse(new SurveyResource($survey,$user,$user->{ucfirst($user->account_type)}));
    }

    public function update(UpdateSurveyRequest $request,$id){
        $user = $request->user();
        $survey = $request->getSurvey();
        DB::beginTransaction();
        $surveyData = SurveyData::fromRequest($request);
        SurveyAnswerClass::deleteAllSurveyAnswersBySurveyId($survey->id);
        $survey->update($surveyData->initializeForUpdate($surveyData));
        $surveyQuestionClass = new SurveyQuestionClass();
        $surveyQuestionClass->createOrUpdateOrDeleteMultiple($surveyData->questions,$survey->id);
        DB::commit();
        ServicesClass::dispatchJob(new SendNewSurveyWaitingApproveNotification($survey,$user));
        return ApiResponseClass::successResponse(new SurveyResource($survey,$user,$user->{ucfirst($user->account_type)}));


    }

    public function softDelete(DestroySurveyRequest $request, $id){
        DB::beginTransaction();
        $survey = $request->getSurvey();
        $survey->softDeleteObject();
        DB::commit();
        return ApiResponseClass::deletedResponse();
    }

    public function destroy(DestroySurveyRequest $request, $id){
        $survey = $request->getSurvey();

        DiscussionCornerServices::deleteSurvey($survey);
        return ApiResponseClass::deletedResponse();
    }

    public function approve(ApproveSurveyRequest $request,$id){
        $user = $request->user();
        $survey = $request->getSurvey();
        $survey->approve();
        ServicesClass::dispatchJob(new SendSurveyApprovalNotification($survey,$user));

        return ApiResponseClass::successMsgResponse();
    }

}
