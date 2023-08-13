<?php

namespace Modules\LearningResource\Http\Controllers\Classes\ManageLearningResourceByAccountType\MyOwnLearningResource;

use Modules\LearningResource\Models\LearningResource;
use Modules\User\Models\School;

class SchoolMyOwnLearningResourceManagement extends BaseManageMyOwnLearningResourceByAccountTypeAbstract
{

    private School $school;
    public function __construct(School $school)
    {
        $this->school = $school;
    }

    public function getMyLearningResourceQuery(){
        $topicQuery = LearningResource::query();
        $topicQuery->myOwnAsSchool($this->school->id);
        return $topicQuery;

    }

}
