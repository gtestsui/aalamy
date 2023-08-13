<?php


namespace Modules\RosterAssignment\Http\Controllers\Classes\ManageRosterAssignment;


use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Modules\Assignment\Http\Controllers\Classes\ManageAssignment\SchoolAssignment;
use Modules\RosterAssignment\Models\RosterAssignment;
use Modules\Roster\Http\Controllers\Classes\ManageRoster\SchoolRosterClass;
use Modules\User\Models\School;

class SchoolRosterAssignment extends BaseRosterAssignmentAbstract
{

    private School $school;

    public function __construct(School $school)
    {
        $this->school = $school;
    }

    /**
     * @return Builder
     */
    protected function myRosterAssignmentsByMyAssignmentsQuery(){
        $rosterClass = new SchoolAssignment($this->school);
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
        $rosterClass = new SchoolRosterClass($this->school);

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
        $assignmentClass = new SchoolAssignment($this->school);
        $assignment = $assignmentClass->myAssignmentByIdOrFail($assignmentId);
        $rosterAssignments = RosterAssignment::where('assignment_id',$assignmentId)
            ->with(['Roster.ClassInfo.ClassModel'])
            ->get();
        return $rosterAssignments;
    }


    /**
     * @return Builder
     */
    protected function myRosterAssignmentsByRosterIdQuery($rosterId)
    {

        $rosterClass = new SchoolRosterClass($this->school);
        $roster = $rosterClass->myRosterByIdOrFail($rosterId);

        $rosterAssignments = RosterAssignment::query()
            ->where('roster_id',$rosterId)
            ->filter($this->filterRosterAssignmentData);

        return $rosterAssignments;
    }



}
