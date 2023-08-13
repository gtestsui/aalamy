<?php


namespace Modules\Assignment\Http\Controllers\Classes\ManageAssignment;


use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Pagination\LengthAwarePaginator;
use Modules\Assignment\Models\Assignment;
use Modules\User\Models\Educator;

class EducatorAssignment extends BaseAssignmentClassAbstract/* implements ManageAssignmentInterface*/
{

    private Educator $educator;

    public function __construct(Educator $educator)
    {
        $this->educator = $educator;
    }

    public function myAssignmentsQuery(){
        $myAssignmentsQuery = Assignment::query();

        $myAssignmentsQuery->where('educator_id',$this->educator->id);
        return $myAssignmentsQuery;
    }


    public function myAssignmentsDoesntLinkedToRoster($rosterId){
        $myAssignmentsDoesntLinkedToRoster = $this->myAssignmentsQuery()
            ->whereDoesntHave('RosterAssignments',function ($query)use ($rosterId){
                return $query->where('roster_id',$rosterId);
            })
            ->get();
        return $myAssignmentsDoesntLinkedToRoster;
    }



}
