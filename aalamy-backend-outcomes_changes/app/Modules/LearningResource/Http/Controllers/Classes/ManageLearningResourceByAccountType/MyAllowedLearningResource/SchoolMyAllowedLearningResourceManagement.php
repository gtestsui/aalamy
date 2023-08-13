<?php

namespace Modules\LearningResource\Http\Controllers\Classes\ManageLearningResourceByAccountType\MyAllowedLearningResource;
use Modules\LearningResource\Models\LearningResource;
use Modules\User\Models\School;

class SchoolMyAllowedLearningResourceManagement extends BaseManageMyAllowedLearningResourceByAccountTypeAbstract
{

    private School $school;
    public function __construct(School $school)
    {
        $this->school = $school;
    }

    public function getMyAllowedLearningResourceQuery(){
        $questionLibraryQuery = LearningResource::query();
        $questionLibraryQuery->myAllowedAsSchool(
            $this->school->id,
            $this->school->user_id,
        );


        return $questionLibraryQuery;

    }

}
