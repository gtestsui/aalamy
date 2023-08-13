<?php

namespace Modules\LearningResource\Http\Controllers\Classes\ManageTopicByAccountType\MyAllwoedTopic;

use App\Http\Controllers\Classes\ApplicationModules;
use Illuminate\Database\Eloquent\Collection;
use Modules\LearningResource\Http\Controllers\Classes\LearningResourceServices;
use Modules\LearningResource\Http\Controllers\Classes\ManageTopicByAccountType\TopicConstants;
use Modules\LearningResource\Models\Topic;
use Modules\User\Http\Controllers\Classes\UserServices;
use Modules\User\Models\Educator;
use Modules\User\Models\Teacher;

class EducatorMyAllowedTopicManagement extends BaseManageMyAllowedTopicByAccountTypeAbstract
{

    private Educator $educator;
    private Collection $myTeacherAccounts;
    private $myTeacherAccountIds = [];
    private $myTeacherSchoolIds = [];
    public $accessType;
    public function __construct(Educator $educator)
    {
        $this->accessType = TopicConstants::READ_ACCESS_TYPE;

        $this->educator = $educator;
        $this->myTeacherAccounts = UserServices::getMyTeacherAccountsSingletone($this->educator);

//        $this->myTeacherAccounts = Teacher::where('user_id',$this->educator->user_id)
//            ->get();
        if(count($this->myTeacherAccounts)){
            $this->myTeacherAccountIds = $this->myTeacherAccounts->pluck('id')->toArray();
            $this->myTeacherSchoolIds = $this->myTeacherAccounts->pluck('school_id')->toArray();
        }
    }

    public function getMyAllowedTopicReadQuery(){

        $topicQuery = Topic::query();
        $topicQuery->myAllowedAsEducator(
            $this->educator->id,
            $this->educator->user_id,
            $this->myTeacherAccountIds,
            $this->myTeacherSchoolIds,
            TopicConstants::READ_ACCESS_TYPE,
        );

        return $topicQuery;
    }

    public function getMyAllowedTopicWriteQuery(){

        $topicQuery = Topic::query();
        $topicQuery->myAllowedAsEducator(
            $this->educator->id,
            $this->educator->user_id,
            $this->myTeacherAccountIds,
            $this->myTeacherSchoolIds,
            TopicConstants::WRITE_ACCESS_TYPE
        );

        return $topicQuery;
    }

    public function checkCanAddInsideTopic(Topic $topic){
//        $topic = Topic::findOrFail($topic_id);
        if(LearningResourceServices::itsMyTopic($topic,$this->educator)){
            return true;
        }

        //the topic belong to my school and have access to write
        if(!is_null($topic->school_id)
            && is_null($topic->teacher_id)
            && in_array($topic->school_id,$this->myTeacherSchoolIds)
            && $topic->write_share_type == configFromModule('panel.learning_resource_write_share_types.school',ApplicationModules::LEARNING_RESOURCE_MODULE_NAME)
        ){
            return true;
        }
        return false;

    }


}
