<?php


namespace Modules\RosterAssignment\Http\Controllers\Classes\ManageRosterAssignmentPage;


use Illuminate\Database\Eloquent\Builder;
use Modules\RosterAssignment\Http\Controllers\Classes\ManageRosterAssignment\TeacherRosterAssignment;
use Modules\Assignment\Models\Page;
use Modules\RosterAssignment\Models\RosterAssignmentPage;
use Modules\User\Models\Teacher;

class TeacherRosterAssignmentPage extends BaseRosterAssignmentPageAbstract
{

    private Teacher $teacher;

    public function __construct(Teacher $teacher)
    {
        $this->teacher = $teacher;
    }

    /**
     * @return Builder|RosterAssignmentPage
     */
//    protected function myRosterAssignmentPagesBelongsToMyRostersByRosterAssignmentIdQuery($rosterAssignmentId){
    protected function myRosterAssignmentPagesByRosterAssignmentIdQuery($rosterAssignmentId){


//        $assignmentClass = new TeacherRosterAssignment($this->teacher);
//        $myRosterAssignment = $assignmentClass->myRosterAssignmentsByMyRostersByRosterAssignmentId($rosterAssignmentId);

        $myAssignmentsPagesQuery = RosterAssignmentPage::query()
            ->where('roster_assignment_id',$rosterAssignmentId);
//            ->isHidden(false)
//            ->isLocked(false);

//        $assignmentClass = new TeacherRosterAssignment($this->teacher);
//        $myRosterAssignment = $assignmentClass->myRosterAssignmentsByMyRostersByRosterAssignmentId($rosterAssignmentId);
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
