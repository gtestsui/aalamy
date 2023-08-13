<?php


namespace Modules\RosterAssignment\Http\Controllers\Classes\ManageRosterAssignmentPage;



use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Modules\Assignment\Models\Page;
use Modules\RosterAssignment\Models\RosterAssignmentPage;

abstract class BaseRosterAssignmentPageAbstract
{

    /**
     * @return Builder|RosterAssignmentPage
     */
//    abstract protected function myRosterAssignmentPagesBelongsToMyRostersByRosterAssignmentIdQuery($rosterAssignmentId);
    abstract protected function myRosterAssignmentPagesByRosterAssignmentIdQuery($rosterAssignmentId);


    /**
     * @return Collection|RosterAssignmentPage
     */
    public function getMyRosterAssignmentPagesByRosterAssignmentId($rosterAssignmentId){
       return $this->myRosterAssignmentPagesByRosterAssignmentIdQuery($rosterAssignmentId)
            ->get();
    }


    /**
     * @return RosterAssignmentPage|null
     */
    public function getMyRosterAssignmentPageByRosterAssignemtIdByPageId($rosterAssignmentId,$pageId){
        $page = $this->myRosterAssignmentPagesByRosterAssignmentIdQuery($rosterAssignmentId)
            ->where('page_id',$pageId)
            ->first();
        return $page;
    }


    /**
     * @return RosterAssignmentPage
     */
    public function getMyRosterAssignmentPageByRosterAssignemtIdByIdOrFail($rosterAssignmentId,$pageId){
        $page = $this->myRosterAssignmentPagesByRosterAssignmentIdQuery($rosterAssignmentId)
            ->where('page_id',$pageId)
            ->firstOrFail();

        return $page;

    }




}
