<?php

namespace Modules\LearningResource\Http\Controllers\Classes\ManageTopicByAccountType\MyOwnTopic;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;
use Modules\LearningResource\Models\LearningResource;
use Modules\LearningResource\Models\Topic;
use Modules\User\Models\Educator;
use Modules\User\Models\Teacher;

class EducatorMyOwnTopicManagement extends BaseManageMyOwnTopicByAccountTypeAbstract
{

    private Educator $educator;
    private Collection $myTeacherAccounts;
    public function __construct(Educator $educator)
    {
        $this->educator = $educator;
        $this->myTeacherAccounts = Teacher::where('user_id',$this->educator->user_id)
            ->get();
    }

    public function getMyOwnTopicQuery(){
        $myTeacherAccountIds = [];
        if(count($this->myTeacherAccounts)){
            $myTeacherAccountIds = $this->myTeacherAccounts->pluck('id')->toArray();
        }

        $topicQuery = Topic::query();
        $topicQuery->myOwnAsEducator($this->educator->id,$myTeacherAccountIds,$this->educator->user_id);
        return $topicQuery;
    }

//    public function getMyOwnTopicJustForDisplayQuery(){
//        $myTeacherAccountIds = [];
//        if(count($this->myTeacherAccounts)){
//            $myTeacherAccountIds = $this->myTeacherAccounts->pluck('id')->toArray();
//        }
//
//        $topicQuery = Topic::query();
////        $topicQuery->myOwnAsEducatorJustForDisplay($this->educator->id,$myTeacherAccountIds,$this->educator->user_id);
//        $topicQuery->myOwnAsEducator($this->educator->id,$myTeacherAccountIds,$this->educator->user_id);
//        return $topicQuery;
//    }



}
