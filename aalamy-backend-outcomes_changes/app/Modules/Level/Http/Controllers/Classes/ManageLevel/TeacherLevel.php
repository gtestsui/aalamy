<?php


namespace Modules\Level\Http\Controllers\Classes\ManageLevel;


use App\Modules\Level\Http\Controllers\Classes\ManageLevel\BaseLevelAbstract;
use Illuminate\Database\Eloquent\Builder;
use Modules\ClassModule\Models\ClassInfo;
use Modules\Level\Models\Level;
use Modules\Level\Models\LevelSubject;
use Modules\TeacherPermission\Http\Controllers\Classes\PermissionConstraints\UnitPermissionClass;
use Modules\User\Models\School;
use Modules\User\Models\Teacher;

class TeacherLevel extends BaseLevelAbstract
{

    protected Teacher $teacher;

    public function __construct(Teacher $teacher)
    {
        $this->teacher = $teacher;
    }

    /**
     * @return Builder
     */
    public function myLevelsQuery(){

        $unitPermissionClass = new UnitPermissionClass($this->teacher);
        if($unitPermissionClass->isHavePermission(['create','update'])){
            $school = School::findOrFail($this->teacher->school_id);
            $schoolLevelClass = new SchoolLevel($school);
            return $schoolLevelClass->myLevelsQuery();
        }else{
            //here we should return teacher levels
            $levelSubjectIds = ClassInfo::where('teacher_id',$this->teacher->id)
                ->pluck('level_subject_id');
            $levelIds = LevelSubject::whereIn('id',$levelSubjectIds)
                ->pluck('level_id');
            $myLevelsQuery = Level::query()->whereIn('id',$levelIds);
            return $myLevelsQuery;
        }

    }

    /**
     * @return Builder
     */
    public function myLevelSubjectsQuery(){

        $unitPermissionClass = new UnitPermissionClass($this->teacher);
        if($unitPermissionClass->isHavePermission(['create','update'])){
            $school = School::findOrFail($this->teacher->school_id);
            $schoolLevelSubjectClass = new SchoolLevel($school);
            return $schoolLevelSubjectClass->myLevelSubjectsQuery();
        }else {
            $myLevelSubjectIds = ClassInfo::where('teacher_id',$this->teacher->id)
                ->pluck('level_subject_id');

            $myLevelSubjectsQuery = LevelSubject::query();
            $myLevelSubjectsQuery->whereIn('id',$myLevelSubjectIds)
                ->filterBy($this->filterByFields);
            return $myLevelSubjectsQuery;

        }

    }



}
