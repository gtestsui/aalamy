<?php

namespace Modules\LearningResource\Http\Controllers\Classes\ManageLearningResourceByAccountType\MyOwnLearningResource;

use Modules\LearningResource\Models\LearningResource;
use Modules\User\Models\Educator;
use Modules\User\Models\Teacher;

class EducatorMyOwnLearningResourceManagement extends BaseManageMyOwnLearningResourceByAccountTypeAbstract
{

    private Educator $educator;
    public function __construct(Educator $educator)
    {
        $this->educator = $educator;
    }

    public function getMyLearningResourceQuery(){
        $myTeacherAccounts = Teacher::where('user_id',$this->educator->user_id)
            ->get();
        $myTeacherAccountIds = $myTeacherAccounts->pluck('id')->toArray();

        $topicQuery = LearningResource::query();
        $topicQuery->myOwnAsEducator($this->educator->id,$myTeacherAccountIds,$this->educator->user_id);
        return $topicQuery;
    }

}
