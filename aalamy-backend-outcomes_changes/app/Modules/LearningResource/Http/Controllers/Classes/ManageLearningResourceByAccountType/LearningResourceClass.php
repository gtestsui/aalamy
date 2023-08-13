<?php

namespace Modules\LearningResource\Http\Controllers\Classes\ManageLearningResourceByAccountType;

use Modules\LearningResource\Http\Controllers\Classes\ManageTopicByAccountType\TopicClass;
use Modules\LearningResource\Http\DTO\LearningResourceData;
use Modules\LearningResource\Models\LearningResource;
use Modules\LearningResource\Models\Topic;

class LearningResourceClass
{

//    private TopicClass $topicClass;
    public function __construct(){
//        $this->topicClass = new TopicClass();

    }


    /**
     * @param LearningResourceData $learningResourceData
     * @param Topic|null $topic
     * @note if the $parentTopic is null that mean we add topic in root so the default share type is private
     * else that mean we add inside another topic so the share type should be compatible with the parent share type
     */
    public function create(LearningResourceData $learningResourceData,?Topic $topic){
//        $this->topicClass->addShareTypeToAllParentTopics($topic,$learningResourceData->share_type);

        return LearningResource::create($learningResourceData->all());

    }

    public function update(LearningResource $learningResource,string $newShareType){
//        $topic = Topic::findOrFail($learningResource->topic_id);
//        $this->topicClass->updateShareTypeInAllParentTopics($topic,$learningResource->share_type,$newShareType);
        $learningResource->update([
            'share_type' => $newShareType
        ]);
        return $learningResource;
    }

    public function softDelete(LearningResource $learningResource){
//        $topic = Topic::findOrFail($learningResource->topic_id);
//        $this->topicClass->deleteShareTypeFromAlParentTopics($topic,$learningResource->share_type);
        $learningResource->softDeleteObject();

    }

}
