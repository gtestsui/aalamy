<?php


namespace Modules\RosterAssignment\Http\Controllers\Classes\ManageRosterAssignmentPage;


use Illuminate\Database\Eloquent\Builder;
use Modules\RosterAssignment\Http\Controllers\Classes\ManageRosterAssignment\SchoolRosterAssignment;
use Modules\Assignment\Models\Page;
use Modules\RosterAssignment\Models\RosterAssignmentPage;
use Modules\User\Models\School;

class SchoolRosterAssignmentPage extends BaseRosterAssignmentPageAbstract
{

    private School $school;

    public function __construct(School $school)
    {
        $this->school = $school;
    }


    /**
     * @return Builder|RosterAssignmentPage
     */
//    protected function myRosterAssignmentPagesBelongsToMyRostersByRosterAssignmentIdQuery($rosterAssignmentId){
    protected function myRosterAssignmentPagesByRosterAssignmentIdQuery($rosterAssignmentId){


//        $assignmentClass = new SchoolRosterAssignment($this->school);
//        $myRosterAssignment = $assignmentClass->myRosterAssignmentsByMyRostersByRosterAssignmentId($rosterAssignmentId);

        $myAssignmentsPagesQuery = RosterAssignmentPage::query()
            ->where('roster_assignment_id',$rosterAssignmentId);
//            ->isHidden(false)
//            ->isLocked(false);

//        $assignmentClass = new SchoolRosterAssignment($this->school);
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
