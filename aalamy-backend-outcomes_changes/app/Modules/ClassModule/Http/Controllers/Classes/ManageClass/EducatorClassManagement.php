<?php

namespace Modules\ClassModule\Http\Controllers\Classes\ManageClass;

use App\Exceptions\ErrorMsgException;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Modules\ClassModule\Models\ClassInfo;
use Modules\ClassModule\Models\ClassModel;
use Modules\Level\Http\Controllers\Classes\ManageLevel\EducatorLevel;
use Modules\User\Models\Educator;

class EducatorClassManagement extends BaseManageClassAbstract implements ManageClassInterface
{

    private Educator $educator;
    public function __construct(Educator $educator)
    {
        $this->educator = $educator;
    }

    public function myClassesQuery():Builder
    {
        $manageLevelClass = new EducatorLevel($this->educator);
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
            ->where('educator_id',$this->educator->id);
        return $classInfosQuery;
    }



}
