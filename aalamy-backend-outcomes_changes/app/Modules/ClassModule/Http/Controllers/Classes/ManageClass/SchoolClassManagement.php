<?php

namespace Modules\ClassModule\Http\Controllers\Classes\ManageClass;

use App\Exceptions\ErrorMsgException;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Modules\ClassModule\Models\ClassInfo;
use Modules\ClassModule\Models\ClassModel;
use Modules\Level\Http\Controllers\Classes\ManageLevel\EducatorLevel;
use Modules\Level\Http\Controllers\Classes\ManageLevel\SchoolLevel;
use Modules\User\Models\Educator;
use Modules\User\Models\School;

class SchoolClassManagement extends BaseManageClassAbstract implements ManageClassInterface
{

    private School $school;
    public function __construct(School $school)
    {
        $this->school = $school;
    }

    public function myClassesQuery():Builder
    {
        $manageLevelClass = new SchoolLevel($this->school);
        $myLevels = $manageLevelClass->myLevels();
        $myLevelIds = $myLevels->pluck('id');
        $myClassesQuery = ClassModel::query()
            ->whereIn('level_id',$myLevelIds);
        return $myClassesQuery;
    }

    /**
     * @return Builder
     */
    public function getMyClassInfoByClassIdQuery($class_id){
        $classInfosQuery = ClassInfo::query()
            ->where('class_id',$class_id)
            ->where('school_id',$this->school->id);
        return $classInfosQuery;
    }

}
