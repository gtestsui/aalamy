<?php

namespace Modules\LearningResource\Http\Controllers\Classes\ManageLearningResourceByAccountType\MyAllowedLearningResource;

use Illuminate\Database\Eloquent\Collection;
use Modules\LearningResource\Models\LearningResource;
use Modules\User\Http\Controllers\Classes\UserServices;
use Modules\User\Models\Educator;
use Modules\User\Models\Teacher;

class EducatorMyAllowedLearningResourceManagement extends BaseManageMyAllowedLearningResourceByAccountTypeAbstract
{

    private Educator $educator;
    private ?Collection $myTeacherAccounts;

    public function __construct(Educator $educator)
    {
        $this->educator = $educator;
        $this->myTeacherAccounts = UserServices::getMyTeacherAccountsSingletone($this->educator);

//        $this->myTeacherAccounts = Teacher::where('user_id',$this->educator->user_id)
//            ->get();
    }

    public function getMyAllowedLearningResourceQuery(){
        $myTeacherAccountIds = [];
        $myTeacherSchoolIds = [];
        if(count($this->myTeacherAccounts)){
            $myTeacherAccountIds = $this->myTeacherAccounts->pluck('id')->toArray();
            $myTeacherSchoolIds = $this->myTeacherAccounts->pluck('school_id')->toArray();
        }


        $learningResourceQuery = LearningResource::query();
        $learningResourceQuery->myAllowedAsEducator(
            $this->educator->id,
            $this->educator->user_id,
            $myTeacherAccountIds,
            $myTeacherSchoolIds
        );



        return $learningResourceQuery;
    }

}
