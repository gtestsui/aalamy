<?php

namespace Modules\DiscussionCorner\Http\Controllers\SuperAdminControllers;

use App\Http\Controllers\Classes\ApiResponseClass;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Modules\DiscussionCorner\Http\Resources\SurveyResource;
use Modules\DiscussionCorner\Http\Resources\SurveyUserAnswerResource;
use Modules\DiscussionCorner\Models\DiscussionCornerSurvey;
use Modules\DiscussionCorner\Models\DiscussionCornerSurveyUserAnswer;

class Super_SurveyController extends Controller
{

    public function paginate(Request $request,$soft_delete=null){
        $posts = DiscussionCornerSurvey::search($request->key,[],[
                'User','School','Educator.User'
            ])
            ->trashed($soft_delete)
            ->withCount('SurveyUsers')
            ->with(['School','Educator.User','User'])
            ->paginate(config('panel.admin_paginate_num'));
        return ApiResponseClass::successResponse(SurveyResource::collection($posts));
    }


    public function showWithAnswers(Request $request,$survey_id){
        $user = $request->user();
        $survey = DiscussionCornerSurvey::with(['SurveyQuestions'=>function($query){
                return $query->with([
                    'Choices.LimitedSurveyUserAnswers.SurveyUser.User.School',
                    'LimitedSurveyUserAnswers.SurveyUser.User.School'
                ])
                ->withSum('Choices as answered_user_count','counter');
            },'User'])
            ->findOrFail($survey_id);

        return ApiResponseClass::successResponse(new SurveyResource($survey));
    }


    public function getUserAnswersByQuestionId(Request $request,$question_id){
        $user = $request->user();
        $userAnswers = DiscussionCornerSurveyUserAnswer::where('question_id',$question_id)
            ->with('SurveyUser.User')
            ->paginate(config('DiscussionCorner.panel.survey_user_answer_count_per_question'));
        return ApiResponseClass::successResponse(SurveyUserAnswerResource::collection($userAnswers));
    }


    public function getUserAnswersByChoiceId(Request $request,$choice_id){
        $user = $request->user();
        $userAnswers = DiscussionCornerSurveyUserAnswer::where('choice_id',$choice_id)
            ->with('SurveyUser.User')
            ->paginate(config('DiscussionCorner.panel.survey_user_answer_count_per_question'));
        return ApiResponseClass::successResponse(SurveyUserAnswerResource::collection($userAnswers));
    }


    public function softDeleteOrRestore(Request $request,$survey_id){
        DB::beginTransaction();
        $survey = DiscussionCornerSurvey::withDeletedItems()
            ->findOrFail($survey_id);
        $survey->softDeleteOrRestore();
        DB::commit();
        return ApiResponseClass::successResponse(new SurveyResource($survey));

    }

    public function destroy(Request $request,$survey_id){
        DB::beginTransaction();
        $survey = DiscussionCornerSurvey::withDeletedItems()
            ->findOrFail($survey_id);
        $survey->delete();
        DB::commit();
        return ApiResponseClass::deletedResponse();

    }

}
