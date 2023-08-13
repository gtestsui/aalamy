<?php


namespace Modules\Level\Http\Controllers\Classes\ManageSubject;


use App\Modules\Level\Http\Controllers\Classes\ManageSubject\BaseSubjectAbstract;
use Modules\ClassModule\Models\ClassInfo;
use Modules\Level\Models\LevelSubject;
use Modules\Level\Models\Subject;
use Modules\TeacherPermission\Http\Controllers\Classes\PermissionConstraints\UnitPermissionClass;
use Modules\User\Models\School;
use Modules\User\Models\Teacher;

class TeacherSubject extends BaseSubjectAbstract
{

    protected Teacher $teacher;
    public function __construct(Teacher $teacher)
    {
        $this->teacher = $teacher;
    }


    public function mySubjectsQuery(){
        $unitPermissionClass = new UnitPermissionClass($this->teacher);
        if($unitPermissionClass->isHavePermission(['create','update'])){
            $school = School::findOrFail($this->teacher->school_id);
            $schoolLevelClass = new SchoolSubject($school);
            return $schoolLevelClass->mySubjectsQuery();
        }else {
            $levelSubjectIds = ClassInfo::where('teacher_id', $this->teacher->id)
                ->pluck('level_subject_id');
            $subjectIds = LevelSubject::whereIn('id', $levelSubjectIds)
                ->pluck('subject_id');
            $mySubjectsQuery = Subject::query();
            $mySubjectsQuery->whereIn('id', $subjectIds);
        }
        return $mySubjectsQuery;
    }


//    public function mySubjects($subjectId=null):Collection
//    {
//        $mySubjectsQuery = $this->mySubjectsQuery($subjectId);
//        $subjects = $mySubjectsQuery->get();
//        return $subjects;
//    }
//
//    /**
//     * if @param int $levelId is null then will ignore the condition
//     * else will retrieve the subjects doesn't belong to this level before
//     */
//    public function mySubjectsExceptBelongsToLevel(?int $levelId): Collection
//    {
//        $mySubjectsQuery = $this->mySubjectsQuery();
//        $subjects = $mySubjectsQuery->whereDoesntHave('LevelSubjects',function ($query)use ($levelId){
//            $query->where('level_id',$levelId);
//        })
//            ->get();
//        return  $subjects;
//    }





}
