<?php


namespace Modules\User\Http\Controllers\Classes\AccountDetails;


use App\Modules\User\Http\Resources\EducatorResource;
use App\Modules\User\Http\Resources\ParentResource;
use Illuminate\Database\Eloquent\Collection;
use Modules\Assignment\Http\Controllers\Classes\ManageAssignment\EducatorAssignment;
use Modules\Assignment\Http\Controllers\Classes\ManageAssignment\SchoolAssignment;
use Modules\ClassModule\Models\ClassInfo;
use Modules\Level\Http\Controllers\Classes\ManageLevel\EducatorLevel;
use Modules\Level\Http\Controllers\Classes\ManageLevel\SchoolLevel;
use Modules\Level\Http\Controllers\Classes\ManageSubject\EducatorSubject;
use Modules\Roster\Http\Controllers\Classes\ManageRoster\EducatorRosterClass;
use Modules\Roster\Http\Controllers\Classes\ManageRoster\SchoolRosterClass;
use Modules\Roster\Models\Roster;
use Modules\User\Http\Controllers\Classes\ManageStudent\StudentEducatorClass;
use Modules\User\Http\Controllers\Classes\ManageStudent\StudentParentClass;
use Modules\User\Http\Controllers\Classes\ManageStudent\StudentSchoolClass;
use Modules\User\Models\Educator;
use Modules\User\Models\ParentModel;
use Modules\User\Models\ParentStudent;
use Modules\User\Models\School;
use Modules\User\Models\Teacher;

class ParentDetailsClass
{

    private ParentModel $parent;
    private StudentParentClass $studentParentClass;
    public function __construct(ParentModel $parent)
    {
        $this->parent = $parent;
        $this->studentParentClass = new StudentParentClass($this->parent);
    }

    public function getDetails():array
    {
        return [
          'parent' => new ParentResource($this->parent),
          'my_childes' => $this->myChildes(),


        ];
    }

    /**
     * @return Collection|ParentStudent
     */
    public function myChildes()
    {
        $myStudents = $this->studentParentClass->myStudentsWithRelation();
//        $count = $myStudents->count();
        return $myStudents;
    }

//    public function myChildes():int
//    {
//        $myTeachers = Teacher::where('user_id',$this->educator->user_id)
//            ->get();
//        $count = $myTeachers->count();
//        return $count;
//    }
//
//    public function myStudentParentsCount():int
//    {
//        $myStudentParent = $this->studentEducatorClass->myStudentParentsAll();
//        $count = $myStudentParent->count();
//        return  $count;
//    }
//
//    public function myAssignmentsCount():int
//    {
//        $schoolAssignment = new EducatorAssignment($this->educator);
//        $myAssignments = $schoolAssignment->myAssignments();
//        $count = $myAssignments->count();
//        return  $count;
//    }
//
//    public function mySubjectsCount():int
//    {
//        $educatorSubjectClass = new EducatorSubject($this->educator);
//        $mySubjects = $educatorSubjectClass->mySubjects();
//        $count = $mySubjects->count();
//        return  $count;
//
//    }
//
//    public function myRostersCount():int
//    {
//        $schoolRosterClass = new EducatorRosterClass($this->educator);
//        $myRosters = $schoolRosterClass->myRosters();
//        $count = $myRosters->count();
//        return  $count;
//    }

}
