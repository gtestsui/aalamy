<?php


namespace Modules\RosterAssignment\Http\Controllers\Classes\ManageRosterAssignment;


use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Modules\Assignment\Http\Controllers\Classes\ManageAssignment\EducatorAssignment;
use Modules\RosterAssignment\Models\RosterAssignment;
use Modules\Roster\Http\Controllers\Classes\ManageRoster\EducatorRosterClass;
use Modules\User\Models\Educator;

class EducatorRosterAssignment extends BaseRosterAssignmentAbstract
{

    private Educator $educator;

    public function __construct(Educator $educator)
    {
        $this->educator = $educator;
    }

    /**
     * @return Builder
     */
    protected function myRosterAssignmentsByMyAssignmentsQuery(){
        $rosterClass = new EducatorAssignment($this->educator);
        $myAssignments = $rosterClass->myAssignments();
        $assignmentIds = $myAssignments->pluck('id')->toArray();

        $myRosterAssignmentsQuery = RosterAssignment::query()
            ->whereIn('assignment_id',$assignmentIds);

        return $myRosterAssignmentsQuery;
    }

    /**
     * @return Builder
     */
    protected function myRosterAssignmentsByMyRostersQuery(){
        $rosterClass = new EducatorRosterClass($this->educator);
        if(isset($this->filterRosterAssignmentData) && count($this->filterRosterAssignmentData->roster_ids))
            $myRosters = $rosterClass->myRosterByIds($this->filterRosterAssignmentData->roster_ids);
        else
            $myRosters = $rosterClass->myRosters();

        $myRosterIds = $myRosters->pluck('id')->toArray();

        $myRosterAssignmentsQuery = RosterAssignment::query()
            ->whereIn('roster_id',$myRosterIds)
            ->filter($this->filterRosterAssignmentData);
//            ->isLocked(false)
//            ->isHidden(false);

        return $myRosterAssignmentsQuery;
    }


    /**
     * @return Collection
     */
    public function myRosterAssignmentsByAssignmentId($assignmentId):Collection
    {
        $assignmentClass = new EducatorAssignment($this->educator);
        $assignment = $assignmentClass->myAssignmentByIdOrFail($assignmentId);
        $rosterAssignments = RosterAssignment::where('assignment_id',$assignmentId)
            ->with(['Roster.ClassInfo.ClassModel','Assignment'=>function($query){
                return $query->with(['LevelSubject'=>function($query){
                    return $query->with(['Level','Subject']);
                },'Unit','Lesson','Pages']);
            }])
            ->get();
        return $rosterAssignments;
    }


    /**
     * @return Builder
     */
    protected function myRosterAssignmentsByRosterIdQuery($rosterId)
    {

        $rosterClass = new EducatorRosterClass($this->educator);
        $roster = $rosterClass->myRosterByIdOrFail($rosterId);

        $rosterAssignments = RosterAssignment::query()
            ->where('roster_id',$rosterId)
            ->filter($this->filterRosterAssignmentData);
        return $rosterAssignments;
    }







}
