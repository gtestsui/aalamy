<?php

namespace Modules\ClassModule\Http\Controllers\Classes\ManageClass;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Modules\ClassModule\Models\ClassInfo;
use Modules\ClassModule\Models\ClassModel;
use Modules\Level\Http\Controllers\Classes\ManageLevel\EducatorLevel;
use Modules\Level\Http\Controllers\Classes\ManageLevel\SchoolLevel;
use Modules\TeacherPermission\Http\Controllers\Classes\PermissionServices;
use Modules\User\Models\Educator;
use Modules\User\Models\School;
use Modules\User\Models\Teacher;

class TeacherClassManagement extends BaseManageClassAbstract implements ManageClassInterface
{

    protected Teacher $teacher;
    protected bool $ignorePermissions = false;
    public function __construct(Teacher $teacher)
    {
        $this->teacher = $teacher;
    }

    public function myClassesQuery():Builder
    {
        $havePermission = PermissionServices::isHaveOneOfThisPermissions($this->teacher,[
            'class' => ['create','update','delete'],
        ]);

        if($havePermission && !$this->ignorePermissions){
            $school = School::findOrFail($this->teacher->school_id);
            $schoolClassManagment = new SchoolClassManagement($school);
            return $schoolClassManagment->myClassesQuery();
        }else{
            $classIds = ClassInfo::where('teacher_id',$this->teacher->id)
                ->pluck('class_id')->toArray();
            $myClassesQuery = ClassModel::query()
                ->whereIn('id',$classIds);
            return $myClassesQuery;
        }

    }

    /**
     * sometimes we need to ignore the permissions because our globalQuery(myClassesQuery)
     * if there is a permission will return the school classes
     * and if we ignored will return the classes contained the teacher inside
     */
    public function ignorePermissions(){
        $this->ignorePermissions = true;
        return $this;
    }

    public function getMyClassesInfo(){
        $classInfos = ClassInfo::where('teacher_id',$this->teacher->id)->get();
        return $classInfos;
    }

    /**
     * @return Builder
     */
    public function getMyClassInfoByClassIdQuery($class_id){
        $classInfosQuery = ClassInfo::query()
            ->where('class_id',$class_id)
            ->where('teacher_id',$this->teacher->id);
        return $classInfosQuery;
    }

}
