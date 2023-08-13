<?php

namespace Modules\LearningResource\Http\Controllers;

use App\Http\Controllers\Classes\ApiResponseClass;
use App\Http\Controllers\Controller;
use Modules\LearningResource\Http\Controllers\Classes\LearningResourceServices;
use Modules\LearningResource\Http\Controllers\Classes\ManageLearningResourceByAccountType\MyAllowedLearningResource\MyAllowedLearningResourceByAccountTypeManagementFactory;
use Modules\LearningResource\Http\Controllers\Classes\ManageLearningResourceByAccountType\MyOwnLearningResource\MyOwnLearningResourceByAccountTypeManagementFactory;
use Modules\LearningResource\Http\Controllers\Classes\ManageTopicByAccountType\MyAllwoedTopic\MyAllowedTopicByAccountTypeManagementFactory;
use Modules\LearningResource\Http\Controllers\Classes\ManageTopicByAccountType\MyOwnTopic\MyOwnTopicByAccountTypeManagementFactory;
use Modules\LearningResource\Http\Controllers\Classes\ManageTopicByAccountType\TopicClass;
use Modules\LearningResource\Http\DTO\TopicData;
use Modules\LearningResource\Http\Requests\Topic\DestroyTopicRequest;
use Modules\LearningResource\Http\Requests\Topic\GetMyAllowedContentTopicIdRequest;
use Modules\LearningResource\Http\Requests\Topic\GetMyOwnContentTopicIdRequest;
use Modules\LearningResource\Http\Requests\Topic\GetMyRootTopicsRequest;
use Modules\LearningResource\Http\Requests\Topic\GetMyTopicsByTopicIdRequest;
use Modules\LearningResource\Http\Requests\Topic\StoreTopicRequest;
use Modules\LearningResource\Http\Requests\Topic\UpdateTopicRequest;
use Modules\LearningResource\Http\Resources\LearningResourceResource;
use Modules\LearningResource\Http\Resources\TopicResource;
use Modules\LearningResource\Models\Topic;
use Modules\User\Http\Controllers\Classes\UserServices;


class TopicController extends Controller
{


    public function getMyOwnRootTopicsPaginate(GetMyRootTopicsRequest $request){
        $user = $request->user();
        $topiceClass = MyOwnTopicByAccountTypeManagementFactory::create($user);
        $myTopics = $topiceClass->getMyRootTopicPaginate();
        return ApiResponseClass::successResponse(TopicResource::collection($myTopics));
    }

    public function getMyAllowedRootTopicsPaginate(GetMyRootTopicsRequest $request){
        $user = $request->user();
        list($accountType,$accountObject) = UserServices::getAccountTypeAndObject($user,true);

        $topiceClass = MyAllowedTopicByAccountTypeManagementFactory::create($user);
        $topics = $topiceClass->getMyAllowedRootTopicPaginate();
        return ApiResponseClass::successResponse(TopicResource::CustomCollection($topics,$accountType,$accountObject));
    }

    /**
     * @note if the client send content_type so he can get just the needed values
     * depends on the content_type value('learning_resource or topics)
     * @note if we will return the two types of data
     * we should check on count $learningResources and $topics and make the empty one as null
     */
    public function getMyOwnContentByTopicId(GetMyOwnContentTopicIdRequest $request,$topic_id){
        $user = $request->user();
        $topicsResponse = null;
        $learningResourcesResponse = null;
        $selectedTopic = Topic::findOrFail($topic_id);
        list($accountType,$accountObject) = UserServices::getAccountTypeAndObject($user,true);

        if(LearningResourceServices::clientIsNeedContentOfTopics($request->content_type)){
            $topicClass = MyOwnTopicByAccountTypeManagementFactory::createByAccountTypeAndObject($accountType,$accountObject);
            $topics = $topicClass->getMyTopicByTopicIdPaginate($topic_id);
            $topicsResponse = count($topics)?TopicResource::CustomCollection($topics,$accountType,$accountObject):null;
        }

        if(LearningResourceServices::clientIsNeedContentOfLearningResource($request->content_type)){
            $learningResourceClass = MyOwnLearningResourceByAccountTypeManagementFactory::createByAccountTypeAndObject($accountType,$accountObject);
            $learningResources = $learningResourceClass->getMyLearningResourceByTopicIdPaginate($topic_id);
            $learningResourcesResponse = count($learningResources)?LearningResourceResource::collection($learningResources):null;
        }

        return ApiResponseClass::successResponse([
           'topics' =>  $topicsResponse,
           'learning_resources' =>  $learningResourcesResponse,
           'can_add_inside_this_topic' =>  true,
           'selected_topic' =>  $selectedTopic,

        ]);
    }

    public function getMyAllowedContentByTopicId(GetMyAllowedContentTopicIdRequest $request,$topic_id){
        $user = $request->user();
        $topicsResponse = null;
        $learningResourcesResponse = null;
        list($accountType,$accountObject) = UserServices::getAccountTypeAndObject($user,true);
        //we have decalre out of the if statement because we need it in the response
        $topicClass = MyAllowedTopicByAccountTypeManagementFactory::createByAccountTypeAndObject($accountType,$accountObject);
        $selectedTopic = $topicClass->getMyAllowedTopicById($topic_id)->load('Parent');
        if(LearningResourceServices::clientIsNeedContentOfTopics($request->content_type)) {
            $topics = $topicClass->getMyAllowedTopicByTopicIdPaginate($topic_id);
            $topicsResponse = count($topics)?TopicResource::CustomCollection($topics,$accountType,$accountObject):null;

        }
        if(LearningResourceServices::clientIsNeedContentOfLearningResource($request->content_type)) {
            $learningResourceClass = MyAllowedLearningResourceByAccountTypeManagementFactory::createByAccountTypeAndObject($accountType,$accountObject/*,$user*/);
            $learningResources = $learningResourceClass->getMyAllowedLearningResourceByTopicIdPaginate($topic_id);
            $learningResourcesResponse = count($learningResources)?LearningResourceResource::CustomCollection($learningResources,$accountType,$accountObject):null;
        }

        return ApiResponseClass::successResponse([
            'selected_topic' =>  $selectedTopic,
            'topics' =>  $topicsResponse,
            'learning_resources' =>  $learningResourcesResponse,
            'can_add_inside_this_topic' =>  $topicClass->checkCanAddInsideTopic($selectedTopic),
        ]);
    }

    public function getMyOwnTopicsByTopicIdPaginate(GetMyTopicsByTopicIdRequest $request){
        $user = $request->user();
        $topicClass = MyOwnTopicByAccountTypeManagementFactory::create($user);
        $myTopics = $topicClass->getMyTopicByTopicIdPaginate($request->topic_id);
        return ApiResponseClass::successResponse(TopicResource::collection($myTopics));
    }

    public function store(StoreTopicRequest $request){
        $user = $request->user();
        $topicData = TopicData::fromRequest($request);
//        dd($topicData->all());
        $topicClass = new TopicClass();
        $topic = $topicClass->create($topicData);
        return ApiResponseClass::successResponse(new TopicResource($topic));
    }

    public function update(UpdateTopicRequest $request,$id){
        $user = $request->user();
        $topic = $request->getTopic();
        $topicData = TopicData::fromRequest($request,$topic);
        $topic->update($topicData->initializeForUpdate());
        return ApiResponseClass::successResponse($topic);
    }


    public function softDelete(DestroyTopicRequest $request,$id){
        $user = $request->user();
        $topic = $request->getTopic();
        $topic->softDeleteObject();
        return ApiResponseClass::deletedResponse();
    }


}
