<?php


namespace Modules\RosterAssignment\Http\Controllers\Classes\ManageRosterAssignmentPage;


use Illuminate\Database\Eloquent\Builder;
use Modules\RosterAssignment\Http\Controllers\Classes\ManageRosterAssignment\StudentRosterAssignment;
use Modules\Assignment\Models\Page;
use Modules\RosterAssignment\Models\RosterAssignmentPage;
use Modules\User\Models\Student;

class StudentRosterAssignmentPage extends BaseRosterAssignmentPageAbstract
{

    private Student $student;

    public function __construct(Student $student)
    {
        $this->student = $student;
    }

    /**
     * @return Builder|RosterAssignmentPage
     */
//    protected function myRosterAssignmentPagesBelongsToMyRostersByRosterAssignmentIdQuery($rosterAssignmentId){
    protected function myRosterAssignmentPagesByRosterAssignmentIdQuery($rosterAssignmentId){


//        $assignmentClass = new StudentRosterAssignment($this->student);
//        $myRosterAssignment = $assignmentClass->myRosterAssignmentsByMyRostersByRosterAssignmentIdOrFail($rosterAssignmentId);

        $myAssignmentsPagesQuery = RosterAssignmentPage::query()
            ->where('roster_assignment_id',$rosterAssignmentId)
            ->isHidden(false)
//            ->isLocked(false)
            ->whereHas('RosterAssignmentStudentPages',function ($query){
                return $query->where('student_id',$this->student->id)
                    ->isHidden(false);
//                    ->isLocked(false);
            });


//        $assignmentClass = new StudentRosterAssignment($this->student);
//        $myRosterAssignment = $assignmentClass->myRosterAssignmentsByMyRostersByRosterAssignmentIdOrFail($rosterAssignmentId);
//        $myAssignmentsPagesQuery = Page::query()
//            ->where('assignment_id',$myRosterAssignment->assignment_id)
//            ->whereHas('RosterAssignmentPages',function ($query)use ($myRosterAssignment){
//                return $query->where('roster_assignment_id',$myRosterAssignment->id)
//                    ->isHidden(false)
//                    ->isLocked(false);
//            });

        return $myAssignmentsPagesQuery;
    }



}
