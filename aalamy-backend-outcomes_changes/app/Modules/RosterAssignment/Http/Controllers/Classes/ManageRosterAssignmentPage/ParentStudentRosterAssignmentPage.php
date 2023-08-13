<?php


namespace Modules\RosterAssignment\Http\Controllers\Classes\ManageRosterAssignmentPage;


use Illuminate\Database\Eloquent\Builder;
use Modules\RosterAssignment\Http\Controllers\Classes\ManageRosterAssignment\StudentRosterAssignment;
use Modules\Assignment\Models\Page;
use Modules\RosterAssignment\Models\RosterAssignmentPage;
use Modules\User\Http\Controllers\Classes\UserServices;
use Modules\User\Models\ParentModel;
use Modules\User\Models\Student;

class ParentStudentRosterAssignmentPage extends BaseRosterAssignmentPageAbstract
{

    private ParentModel $parent;
    private Student $student;

    public function __construct(ParentModel $parent/*,Student $student*/)
    {
        $this->parent = $parent;
        $this->student = UserServices::getTargetedStudentByParent(request()->student_id);

//        $this->student = Student::findOrFail(request()->student_id);

//        $this->student = $student;
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
