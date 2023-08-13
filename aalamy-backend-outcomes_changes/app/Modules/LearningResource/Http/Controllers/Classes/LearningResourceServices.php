<?php


namespace Modules\LearningResource\Http\Controllers\Classes;



use App\Exceptions\ErrorMsgException;
use App\Exceptions\ErrorUnAuthorizationException;
use App\Http\Controllers\Classes\ApplicationModules;
use Modules\LearningResource\Http\Controllers\Classes\ManageTopicByAccountType\MyAllwoedTopic\MyAllowedTopicByAccountTypeManagementFactory;
use Modules\LearningResource\Http\Controllers\Classes\ManageTopicByAccountType\MyOwnTopic\MyOwnTopicByAccountTypeManagementFactory;
use Modules\LearningResource\Http\Controllers\Classes\ManageTopicByAccountType\TopicConstants;
use Modules\LearningResource\Http\Resources\LearningResourceResource;
use Modules\LearningResource\Http\Resources\TopicResource;
use Modules\LearningResource\Models\LearningResource;
use Modules\LearningResource\Models\Topic;
use Modules\User\Http\Controllers\Classes\UserServices;
use Modules\User\Models\Educator;
use Modules\User\Models\School;
use Modules\User\Models\Teacher;
use Modules\User\Models\User;

class LearningResourceServices
{

    /**
     * @return Topic
     */
    public static function checkUseTopic(User $user,$topicId){

        $topiceClass  = MyOwnTopicByAccountTypeManagementFactory::create($user);
        $topic = $topiceClass->getMyTopicById($topicId);
        if(is_null($topic)){
            //check if the parent topic it's from my school and i have access to write
            if($user->account_type != 'educator')
                throw new ErrorUnAuthorizationException();

            $topicClass  = MyAllowedTopicByAccountTypeManagementFactory::create($user);
            $topic = $topicClass->changeAccessType(TopicConstants::WRITE_ACCESS_TYPE)
                ->getMyAllowedTopicById($topicId);
            if(is_null($topic))
                throw new ErrorUnAuthorizationException();
        }


//
//        $topiceClass  = MyOwnTopicByAccountTypeManagementFactory::create($user);
//        $topic = $topiceClass->getMyTopicById($topicId);
//        if(is_null($topic))
//            throw new ErrorUnAuthorizationException();
        return $topic;
    }


    /**
     * @return array
     */
    public static function prepareLearningResourceOwner($user,$request,$topic=null){

        if(!is_null($topic)){
            return [$topic->school_id,$topic->teacher_id,$topic->educator_id,];
        }

        if(isset($request->topic_id)){
            return Self::getOwnersFromTopic($request->topic_id);
        }

        return UserServices::prepareOnwer($user,$request);

    }

    /**
     * @return array
     */
    public static function getOwnersFromTopic($topicId){
        $topic = Topic::findOrFail($topicId);
        $schoolId = $topic->school_id;
        $teacherId = $topic->teacher_id;
        $educatorId = $topic->educator_id;
        return [$schoolId,$teacherId,$educatorId];
    }

    /**
     * @param string|null $contentType
     * check if $contentType is equal to topics or not
     */
    public static function clientIsNeedContentOfTopics($contentType){
        if(
            !isset($contentType)
            ||
            $contentType == configFromModule(
                    "panel.topic_content_types.topics",
                    ApplicationModules::LEARNING_RESOURCE_MODULE_NAME
            )
        ){
            return true;
        }

        return false;
    }

    /**
     * @param string|null $contentType
     * check if $contentType is equal to learning_resources or not
     */
    public static function clientIsNeedContentOfLearningResource($contentType){
        if(
            !isset($contentType)
            ||
            $contentType == configFromModule(
                "panel.topic_content_types.learning_resources",
                ApplicationModules::LEARNING_RESOURCE_MODULE_NAME
            )
        ){
            return true;
        }

        return false;
    }


    public static function checkValidShareTypeWithMyAccount($schoolId,$teacherId,$educatorId,string $share_type){

        if($share_type == configFromModule('panel.learning_resource_read_share_types.school',ApplicationModules::LEARNING_RESOURCE_MODULE_NAME)){
            if(is_null($teacherId) && is_null($schoolId))
                throw new ErrorMsgException('invalid share type with your account type');
        }

        if($share_type == configFromModule('panel.learning_resource_read_share_types.my_private_student',ApplicationModules::LEARNING_RESOURCE_MODULE_NAME)){
            if(is_null($educatorId))
                throw new ErrorMsgException('invalid share type with your account type');
        }

    }


    public static function checkValidTopicAccessType($accessType){
        if($accessType != TopicConstants::READ_ACCESS_TYPE
            && $accessType!=TopicConstants::WRITE_ACCESS_TYPE)
            throw new ErrorMsgException('invalid access type');

    }

    /**
     * @param Topic|TopicResource $topic
     */
    public static function topicBelongsToSchool($topic,$schoolId){
        if($topic->school_id == $schoolId && is_null($topic->teacher_id))
            return true;
        return  false;
    }






    /**
     * @param Topic|TopicResource $topic
     * @param Educator|School|Teacher $accountObject
     */
    public static function itsMyTopic($topic,$accountObject){
        if($topic->user_id == $accountObject->user_id)
            return true;
        return false;
    }

    /**
     * @param LearningResource|LearningResourceResource $topic
     */
    public static function learningResourceBelongsToSchool($learningResource,$schoolId){
        if($learningResource->school_id == $schoolId && is_null($learningResource->teacher_id))
            return true;
        return  false;
    }

    /**
     * @param LearningResource|LearningResourceResource $topic
     */
    public static function itsMyLearningResource($learningResource,$accountObject){
        if($learningResource->user_id == $accountObject->user_id)
            return true;
        return false;
    }



}
