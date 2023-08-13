<?php

namespace Modules\LearningResource\Http\Controllers\Classes\ManageTopicByAccountType\MyAllwoedTopic;

use App\Http\Controllers\Classes\ApplicationModules;
use Illuminate\Pagination\LengthAwarePaginator;
use Modules\LearningResource\Http\Controllers\Classes\LearningResourceServices;
use Modules\LearningResource\Http\Controllers\Classes\ManageTopicByAccountType\TopicConstants;
use Modules\LearningResource\Models\LearningResource;
use Modules\LearningResource\Models\Topic;
use Modules\User\Models\School;
use Modules\User\Models\Teacher;

class SchoolMyAllowedTopicManagement extends BaseManageMyAllowedTopicByAccountTypeAbstract
{

    private School $school;
    public $accessType;

    public function __construct(School $school)
    {
        $this->accessType = TopicConstants::READ_ACCESS_TYPE;

        $this->school = $school;
    }

    public function getMyAllowedTopicReadQuery(){
        $topicQuery = Topic::query();
        $topicQuery->myAllowedAsSchool(
            $this->school->id,
            $this->school->user_id,
            TopicConstants::READ_ACCESS_TYPE,
        );
        return $topicQuery;

    }


    public function getMyAllowedTopicWriteQuery(){
        $topicQuery = Topic::query();
        $topicQuery->myAllowedAsSchool(
            $this->school->id,
            $this->school->user_id,
            TopicConstants::WRITE_ACCESS_TYPE
        );
        return $topicQuery;

    }


    public function checkCanAddInsideTopic(Topic $topic){
//        $topic = Topic::findOrFail($topic_id);
        //im the real owner
        if(LearningResourceServices::itsMyTopic($topic,$this->school)){
            return true;
        }

        //if im not the real owner but the topic added inside one of my own topics
        if(LearningResourceServices::topicBelongsToSchool($topic,$this->school->id)
//            && is_null($topic->teacher_id)
            && $topic->write_share_type == configFromModule('panel.learning_resource_write_share_types.school',ApplicationModules::LEARNING_RESOURCE_MODULE_NAME)
        ){
            return true;
        }
        return false;

    }



}
