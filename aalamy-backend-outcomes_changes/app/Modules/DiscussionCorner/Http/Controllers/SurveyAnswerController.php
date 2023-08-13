<?php

namespace Modules\DiscussionCorner\Http\Controllers;

use App\Http\Controllers\Classes\ApiResponseClass;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;

use Modules\DiscussionCorner\Http\Controllers\Classes\SurveyAnswerClass;
use Modules\DiscussionCorner\Http\Requests\SurveyAnswer\GetSurveyUserAnswersByQuestionIdRequest;
use Modules\DiscussionCorner\Http\Requests\SurveyAnswer\GetSurveyUserAnswersByChoiceIdRequest;
use Modules\DiscussionCorner\Http\Requests\SurveyAnswer\StoreSurveyAnswerRequest;

use Modules\DiscussionCorner\Http\Resources\SurveyUserAnswerResource;
use Modules\DiscussionCorner\Models\DiscussionCornerSurveyUser;
use Modules\DiscussionCorner\Models\DiscussionCornerSurveyUserAnswer;

class SurveyAnswerController extends Controller
{


    public function store(StoreSurveyAnswerRequest $request,$survey_id){
        $user = $request->user();
        DB::beginTransaction();
//        dd(array_column($request->answers, 'written_answer'));
        $surveyUser = DiscussionCornerSurveyUser::create([
            'survey_id' => $survey_id,
            'user_id' => $user->id,
        ]);
//        DB::enableQueryLog();
        $surveyAnswerClass = new SurveyAnswerClass();
        $surveyAnswerClass->addSurveyAnswers($surveyUser,$request->answers);
//        dd(DB::getQueryLog());
        DB::commit();
        return ApiResponseClass::successMsgResponse();
    }

    public function getUserAnswersByQuestionId(GetSurveyUserAnswersByQuestionIdRequest $request,$question_id){
        $user = $request->user();
        $userAnswers = DiscussionCornerSurveyUserAnswer::where('question_id',$question_id)
            ->with('SurveyUser.User')
            ->paginate(config('DiscussionCorner.panel.survey_user_answer_count_per_question'));
        return ApiResponseClass::successResponse(SurveyUserAnswerResource::collection($userAnswers));
    }


    public function getUserAnswersByChoiceId(GetSurveyUserAnswersByChoiceIdRequest $request,$choice_id){
        $user = $request->user();
        $userAnswers = DiscussionCornerSurveyUserAnswer::where('choice_id',$choice_id)
            ->with('SurveyUser.User')
            ->paginate(config('DiscussionCorner.panel.survey_user_answer_count_per_question'));
        return ApiResponseClass::successResponse(SurveyUserAnswerResource::collection($userAnswers));
    }





}
