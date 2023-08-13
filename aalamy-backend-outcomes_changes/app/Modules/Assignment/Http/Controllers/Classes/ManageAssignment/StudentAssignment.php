<?php


namespace Modules\Assignment\Http\Controllers\Classes\ManageAssignment;


use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Pagination\LengthAwarePaginator;
use Modules\Assignment\Models\Assignment;
use Modules\RosterAssignment\models\RosterAssignment;
use Modules\Roster\Http\Controllers\Classes\ManageRoster\RosterManagementFactory;
use Modules\Roster\Http\Controllers\Classes\ManageRoster\StudentRosterClass;
use Modules\Roster\Http\Controllers\Classes\RosterServices;
use Modules\User\Models\Educator;
use Modules\User\Models\Student;

class StudentAssignment /*extends BaseAssignmentClassAbstract*//* implements ManageAssignmentInterface*/
{

    private Student $student;

    public function __construct(Student $student)
    {
        $this->student = $student;
    }

//    public function myAssignmentsQuery(){
//        $myAssignmentsQuery = Assignment::query();
//
//        $studentRoster = new StudentRosterClass($this->student);
//        $myRosters = $studentRoster->myRosters();
//        $myRosterIds = $myRosters->pluck('id')->toArray();
//        $assignmentIds = RosterAssignment::whereIn('roster_id',$myRosterIds)
//            ->isLocked(false)
//            ->isHidden(false)
//            ->pluck('assignment_id')->toArray();
//        $myAssignmentsQuery->whereIn('id',$assignmentIds);
//
//        return $myAssignmentsQuery;
//    }



}
