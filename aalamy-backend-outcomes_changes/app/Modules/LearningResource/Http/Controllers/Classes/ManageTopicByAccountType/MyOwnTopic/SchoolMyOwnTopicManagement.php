<?php

namespace Modules\LearningResource\Http\Controllers\Classes\ManageTopicByAccountType\MyOwnTopic;

use Illuminate\Pagination\LengthAwarePaginator;
use Modules\LearningResource\Models\LearningResource;
use Modules\LearningResource\Models\Topic;
use Modules\User\Models\School;
use Modules\User\Models\Teacher;

class SchoolMyOwnTopicManagement extends BaseManageMyOwnTopicByAccountTypeAbstract
{

    private School $school;
    public function __construct(School $school)
    {
        $this->school = $school;
    }

    public function getMyOwnTopicQuery(){
        $topicQuery = Topic::query();
        $topicQuery->myOwnAsSchool($this->school->id,$this->school->user_id);
        return $topicQuery;

    }

//    public function getMyOwnTopicJustForDisplayQuery(){
//        $topicQuery = Topic::query();
////        $topicQuery->myOwnAsSchoolJustForDisplay($this->school->id,$this->school->user_id);
//        $topicQuery->myOwnAsSchool($this->school->id,$this->school->user_id);
//        return $topicQuery;
//
//    }


}
