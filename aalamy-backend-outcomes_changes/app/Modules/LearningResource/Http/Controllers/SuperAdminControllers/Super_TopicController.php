<?php

namespace Modules\LearningResource\Http\Controllers\SuperAdminControllers;

use App\Http\Controllers\Classes\ApiResponseClass;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Modules\DiscussionCorner\Models\DiscussionCornerPost;
use Modules\LearningResource\Http\Controllers\Classes\LearningResourceServices;
use Modules\LearningResource\Http\Resources\LearningResourceResource;
use Modules\LearningResource\Http\Resources\TopicResource;
use Modules\LearningResource\Models\LearningResource;
use Modules\LearningResource\Models\Topic;
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

class Super_TopicController extends Controller
{

    public function paginate(Request $request,$soft_delete=null){
//        Topic::removeWithoutDeletedItemsScopeFromAllModel();

        $topics = Topic::search($request->key,[],[
                'Teacher.User',
                'School',
                'Educator.User',
                'User',
                'Parent',
            ])
            ->trashed($soft_delete)
            ->with([
                'Teacher.User',
                'School',
                'Educator.User',
                'User',
                'Parent',
            ])
            ->paginate(config('panel.admin_paginate_num'));
//        Topic::removeWithoutDeletedItemsScopeFromAllModel();

        return ApiResponseClass::successResponse(TopicResource::collection($topics));
    }

    public function getElementByIdEvenItsDeleted(Request $request,$id,$soft_delete){
        $topic = Topic::with([
                'Teacher.User',
                'School',
                'Educator.User',
                'User',
                'Parent',
            ])
            ->findOrFail($id);

        return ApiResponseClass::successResponse(new TopicResource($topic));

    }

    public function rootPaginate(Request $request,$soft_delete=null){
        $topics = Topic::search($request->key,[],[
            'Teacher.User',
            'School',
            'Educator.User',
            'User',
            'Parent',
        ])
        ->trashed($soft_delete)
        ->with([
            'Teacher.User',
            'School',
            'Educator.User',
            'User',
            'Parent',
        ])
        ->isRoot()
        ->paginate(config('panel.admin_paginate_num'));

        return ApiResponseClass::successResponse(TopicResource::collection($topics));
    }


    public function getContent(Request $request,$topic_id,$soft_delete=null){
        $topicsResponse = null;
        $learningResourcesResponse = null;

        if(LearningResourceServices::clientIsNeedContentOfTopics($request->content_type)){
            $topics = Topic::search($request->key,[],[
                'Teacher.User',
                'School',
                'Educator.User',
                'User',
                'Parent',
            ])
            ->trashed($soft_delete)
            ->where('topic_id',$topic_id)
            ->with([
                'Teacher.User',
                'School',
                'Educator.User',
                'User',
                'Parent',
            ])
            ->paginate(config('panel.admin_paginate_num'));
            $topicsResponse = count($topics)?TopicResource::collection($topics):null;
        }


        if(LearningResourceServices::clientIsNeedContentOfLearningResource($request->content_type)){
            $learningResources = LearningResource::search($request->key,[],[
                'Teacher.User',
                'School',
                'Educator.User',
                'Topic',
                'Unit',
                'Lesson',
                'LevelSubject'=>['Level','Subject']
            ])
            ->trashed($soft_delete)
            ->where('topic_id',$topic_id)
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
            $learningResourcesResponse = count($learningResources)?LearningResourceResource::collection($learningResources):null;
        }

        return ApiResponseClass::successResponse([
            'topics' =>  $topicsResponse,
            'learning_resources' =>  $learningResourcesResponse,

        ]);

    }



    public function softDeleteOrRestore(Request $request,$topic_id){
        DB::beginTransaction();
        $topic = Topic::withDeletedItems()
            ->findOrFail($topic_id);
        $topic->softDeleteOrRestore();
        DB::commit();
        return ApiResponseClass::successResponse(new TopicResource($topic));

    }

    public function destroy(Request $request,$topic_id){
        DB::beginTransaction();
        $topic = Topic::withDeletedItems()
            ->findOrFail($topic_id);
        $topic->delete();
        DB::commit();
        return ApiResponseClass::deletedResponse();

    }

}
