<?php


namespace Modules\Assignment\Http\Controllers\Classes\ManageAssignment;


use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;
use Modules\Assignment\Models\Assignment;
use Modules\User\Models\School;

class SchoolAssignment extends BaseAssignmentClassAbstract/* implements ManageAssignmentInterface*/
{

    private School $school;

    public function __construct(School $school)
    {
        $this->school = $school;
    }

    public function myAssignmentsQuery(){
        $myAssignmentsQuery = Assignment::query();

        //when the teacher store assignment we store school_id and we do that we the school store assignment too
        //so all assignments in school its have school_if
        $myAssignmentsQuery->where('school_id',$this->school->id);
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
