<?php


namespace Modules\RosterAssignment\Http\Controllers\Classes\ManageRosterAssignmentPage;


use Illuminate\Database\Eloquent\Builder;
use Modules\RosterAssignment\Http\Controllers\Classes\ManageRosterAssignment\EducatorRosterAssignment;
use Modules\Assignment\Models\Page;
use Modules\RosterAssignment\Models\RosterAssignmentPage;
use Modules\User\Models\Educator;

class EducatorRosterAssignmentPage extends BaseRosterAssignmentPageAbstract
{

    private Educator $educator;

    public function __construct(Educator $educator)
    {
        $this->educator = $educator;
    }


    /**
     * @return Builder|RosterAssignmentPage
     */
//    protected function myRosterAssignmentPagesBelongsToMyRostersByRosterAssignmentIdQuery($rosterAssignmentId){
    protected function myRosterAssignmentPagesByRosterAssignmentIdQuery($rosterAssignmentId){

//        $assignmentClass = new EducatorRosterAssignment($this->educator);
//        $myRosterAssignment = $assignmentClass->myRosterAssignmentsByMyRostersByRosterAssignmentId($rosterAssignmentId);

        $myAssignmentsPagesQuery = RosterAssignmentPage::query()
            ->where('roster_assignment_id',$rosterAssignmentId);
//            ->isHidden(false)
//            ->isLocked(false);

//        $assignmentClass = new EducatorRosterAssignment($this->educator);
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
