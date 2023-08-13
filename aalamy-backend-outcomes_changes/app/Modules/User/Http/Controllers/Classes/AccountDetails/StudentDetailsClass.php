<?php


namespace Modules\User\Http\Controllers\Classes\AccountDetails;


use App\Modules\User\Http\Resources\StudentResource;
use Illuminate\Database\Eloquent\Collection;
use Modules\Roster\Http\Controllers\Classes\ManageRoster\StudentRosterClass;
use Modules\RosterAssignment\Models\RosterAssignment;
use Modules\StudentAchievement\Models\StudentAchievement;
use Modules\User\Http\Controllers\Classes\StudentClass;
use Modules\User\Models\EducatorStudent;
use Modules\User\Models\SchoolStudent;
use Modules\User\Models\Student;

class StudentDetailsClass
{

    private Student $student;
//    private StudentParentClass $studentParentClass;
    public function __construct(Student $student)
    {
        $this->student = $student;
//        $this->studentParentClass = new StudentParentClass($this->student);
    }

    public function getDetails():array
    {
        return [
          'student' => new StudentResource($this->student),
          'my_school_students' => $this->mySchoolStudent(),
          'my_educator_students' => $this->myEducatorStudent(),
          'my_roster_assignments' => $this->myRosterAssignments(),
          'my_achievements' => $this->getMyAchievements(),


        ];
    }

    /**
     * @return Collection|SchoolStudent
     */
    public function mySchoolStudent()
    {
        $studentClass = new StudentClass();

        $mySchoolStudents = $studentClass->getMySchoolStudent($this->student);
        return $mySchoolStudents;
    }

    /**
     * @return Collection|EducatorStudent
     */
    public function myEducatorStudent(){
        $studentClass = new StudentClass();
        $myEducatorStudents = $studentClass->getMyEducatorStudent($this->student);
        return $myEducatorStudents;
    }

    /**
     * @return RosterAssignment|Collection
     */
    public function myRosterAssignments(){
        $rosterClass = new StudentRosterClass($this->student);
        $myRosters = $rosterClass->myRosters();

        $myRosterIds = $myRosters->pluck('id')->toArray();

        $myRosterAssignments = RosterAssignment::query()
            ->whereIn('roster_id',$myRosterIds)
//            ->filter($this->filterRosterAssignmentData)
            ->with(['Assignment'=>function($query){
                return $query->with(['Educator.User','School','Unit','Lesson',
                    'LevelSubject'=>function($query){
                        return $query->with(['Level','Subject']);
                    },'Pages'=>function($query){
                        return $query->orderBy('order','asc')->first();
                    }]);
            }])
            ->get();

        return $myRosterAssignments;
    }

    public function getMyAchievements(){
        $achievements = StudentAchievement::where('student_id',$this->student->id)
            ->with('User')
            ->get();

        return $achievements;

    }


}
