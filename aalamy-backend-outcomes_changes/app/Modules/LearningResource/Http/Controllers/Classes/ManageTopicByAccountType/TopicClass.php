<?php

namespace Modules\LearningResource\Http\Controllers\Classes\ManageTopicByAccountType;

use Modules\LearningResource\Http\DTO\TopicData;
use Modules\LearningResource\Models\Topic;

class TopicClass
{

    public function __construct(){

    }

    /**
     * @param TopicData $topicData
     * @param Topic|null $parentTopic
     * @note the first state of share_types in Topic model is [private]
     *
     */
    public function create(TopicData $topicData){
        return Topic::create($topicData->all());
    }


    /**
     * @note add the type from shareTypes
     * to $topic and all parent topics of $topic
     */
    public function addShareTypeToAllParentTopics(Topic $topic,string $shareType){
        $topicShareTypes = $topic->share_types;
        array_push($topicShareTypes,$shareType);
        $this->updateTopicShareTypes($topic,$topicShareTypes);
        while (!is_null($topic->topic_id)){
            $topic = Topic::findOrFail($topic->topic_id);
            $topicShareTypes = $topic->share_types;
            array_push($topicShareTypes,$shareType);
            $this->updateTopicShareTypes($topic,$topicShareTypes);
        }
    }


    /**
     * @note remove the old type from shareTypes
     * for $topic and all parent topics of $topic and then insert the new one in each one
     */
    public function updateShareTypeInAllParentTopics(Topic $topic,string $oldShareType,string $newShareType){

        $topicShareTypes = $topic->share_types;
        deleteFromArray($topicShareTypes,$oldShareType);
        array_push($topicShareTypes,$newShareType);

        $topic = $this->updateTopicShareTypes($topic,$topicShareTypes);
        while (!is_null($topic->topic_id)){
            $topic = Topic::findOrFail($topic->topic_id);
            $topicShareTypes = $topic->share_types;
            deleteFromArray($topicShareTypes,$oldShareType);
            array_push($topicShareTypes,$newShareType);
            $topic = $this->updateTopicShareTypes($topic,$topicShareTypes);
        }
    }

    /**
     * @note remove the type from shareTypes
     * for $topic and all parent topics of $topic
     */
    public function deleteShareTypeFromAlParentTopics(Topic $topic,string $shareType){
        $topicShareTypes = $topic->share_types;
        deleteFromArray($topicShareTypes,$shareType);

        $topic = $this->updateTopicShareTypes($topic,$topicShareTypes);
        while (!is_null($topic->topic_id)){
            $topic = Topic::findOrFail($topic->topic_id);
            $topicShareTypes = $topic->share_types;
            deleteFromArray($topicShareTypes,$shareType);
            $topic = $this->updateTopicShareTypes($topic,$topicShareTypes);
        }
    }


    public function updateTopicShareTypes(Topic $topic,array $newShareTypes){
        $topic->update([
            'share_types' => $newShareTypes
        ]);
        return $topic;
    }





}
