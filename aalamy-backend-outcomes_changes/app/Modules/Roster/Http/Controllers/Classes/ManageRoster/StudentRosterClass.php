<?php


namespace Modules\Roster\Http\Controllers\Classes\ManageRoster;


use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Modules\ClassModule\Http\Controllers\Classes\ManageClass\StudentClassManagement;
use Modules\ClassModule\Models\ClassStudent;
use Modules\Roster\Models\Roster;
use Modules\User\Models\Student;

class StudentRosterClass extends BaseManageRosterAbstract  implements ManageRosterInterface
{

    private Student $student;
    public function __construct(Student $student)
    {
        $this->student = $student;
    }

    /**
     * @return Builder
     */
    public function myRostersQuery(){

        $manageClass = new StudentClassManagement($this->student);
        $myClasses = $manageClass->myClasses();
        $myClassesIds = $myClasses->pluck('id')->toArray();

        $myClassStudents = ClassStudent::where('student_id',$this->student->id)
            ->whereIn('class_id',$myClassesIds)
            ->active()
            ->get();

        $myClassStudentIds = $myClassStudents->pluck('id')->toArray();

        $myRostersQuery = Roster::query();
        $myRostersQuery->whereHas('RosterStudents',function ($q)use($myClassStudentIds){
            return $q->whereIn('class_student_id',$myClassStudentIds);
        });

        return  $myRostersQuery;

    }



    public function myRostersGroupedBy(){

        $myRosters = $this->myRostersQuery()
            ->with(['ClassInfo'=>function($query){
                return $query->with(['School.User',
                    'Teacher.User',
                    'Educator.User',
                    'LevelSubject'=>function($query){
                        return $query->with(['Level','Subject']);
                    }]);
            }])
            ->get()
            ->groupBy('ClassInfo.id');
//        return $myRosters->values()->all();
        return $myRosters;
    }


    /**
     *
     */
    public function myRosterByIdWithRosterAssignment($id){
        $myRosterWithRosterAssignment = $this->myRosterByIdQuery($id)
            ->with('AvailableRosterAssignments.Assignment')
            ->firstOrFail();
        return $myRosterWithRosterAssignment;

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
            ->with('ClassInfo.Teacher')
            ->orderBy('class_info_id','asc')
            ->get();
        return $myRosters;
    }

}
