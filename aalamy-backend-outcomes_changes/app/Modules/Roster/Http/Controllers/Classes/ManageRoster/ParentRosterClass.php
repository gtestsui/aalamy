<?php


namespace Modules\Roster\Http\Controllers\Classes\ManageRoster;


use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Modules\ClassModule\Http\Controllers\Classes\ManageClass\EducatorClassManagement;
use Modules\ClassModule\Http\Controllers\Classes\ManageClass\StudentClassManagement;
use Modules\ClassModule\Models\ClassInfo;
use Modules\ClassModule\Models\ClassModel;
use Modules\ClassModule\Models\ClassStudent;
use Modules\Level\Http\Controllers\Classes\ManageLevel\EducatorLevel;
use Modules\Level\Http\Controllers\Classes\ManageLevel\SchoolLevel;
use Modules\Roster\Models\Roster;
use Modules\User\Http\Controllers\Classes\ManageStudent\StudentParentClass;
use Modules\User\Models\Educator;
use Modules\User\Models\ParentModel;
use Modules\User\Models\School;
use Modules\User\Models\Student;

class ParentRosterClass extends BaseManageRosterAbstract  implements ManageRosterInterface
{

    private ParentModel $parent;
    public function __construct(ParentModel $parent)
    {
        $this->parent = $parent;
    }

    /**
     * @return Builder
     */
    public function myRostersQuery(){
        $studentParentClass = new StudentParentClass($this->parent);
        $myStudents = $studentParentClass->myStudents();
        $myStudentIds = $myStudents->pluck('id')->toArray();
//        $manageClass = new StudentClassManagement($this->student);
//        $myClasses = $manageClass->myClasses();

        {
            $myClassIds = ClassStudent::whereIn('student_id',$myStudentIds)
                ->with('RosterStudents.Roster')->get();
        }

        $myClassIds = ClassStudent::whereIn('student_id',$myStudentIds)
            ->pluck('class_id')->toArray();
        $myClasses = ClassModel::whereIn('id',$myClassIds);
        $myClassesIds = $myClasses->pluck('id')->toArray();

        $myClassStudents = ClassStudent::whereIn('student_id',$myStudentIds)
            ->whereIn('class_id',$myClassesIds)
            ->active()
            ->get();

        $myRostersQuery = Roster::query();
        $myRostersQuery->whereHas('RosterStudents',function ($q)use($myClassStudents){
            return $q->whereIn('class_student_id',$myClassStudents->pluck('id')->toArray());
        });

        return  $myRostersQuery;

    }


    public function myRostersGroupedBy(){


        $studentParentClass = new StudentParentClass($this->parent);
        $myStudents = $studentParentClass->myStudents();
        $myStudentIds = $myStudents->pluck('id')->toArray();
        $myClassStudents = ClassStudent::whereIn('student_id',$myStudentIds)
            ->with('Student')
            ->with(['RosterStudents.Roster.ClassInfo'=>function($query){
                return $query->with(['School.User',
                    'Teacher.User',
                    'Educator.User',
                    'LevelSubject'=>function($query){
                        return $query->with(['Level','Subject']);
                    }]);
            }])
            ->get()
            ->groupBy('id');
//            ->values()->all();
        return $myClassStudents;


//        $myRosters = $this->myRostersQuery()
//            ->with(['ClassInfo'=>function($query){
//                return $query->with(['School.User',
//                    'Teacher.User',
//                    'Educator.User',
//                    'LevelSubject'=>function($query){
//                        return $query->with(['Level','Subject']);
//                    }]);
//            }])
//            ->get()
//            ->groupBy('ClassInfo.id');
//        return $myRosters->values()->all();
//        return(json_decode($myRosters,true));
//        return $myRosters;
    }

    /**
     * @param mixed $classId
     * @return Collection of Roster
     */
    public function allMyRostersByClassId($classId){
        $manageClass = new StudentClassManagement($this->student);
        $myClass = $manageClass->myClassesByIdOrFail($classId);

        $myClassStudent = ClassStudent::where('student_id',$this->student->id)
            ->where('class_id',$myClass->id)
            ->active()
            ->first();

        $myRosters = Roster::whereHas('RosterStudents',function ($q)use($myClassStudent){
                return $q->where('class_student_id',$myClassStudent->id);
            })
            ->get();
        return $myRosters;
    }

}
