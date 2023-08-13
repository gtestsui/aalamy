<?php


namespace Modules\Assignment\Http\Controllers\Classes\ManageAssignment;


use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;
use Modules\Assignment\Models\Assignment;
use Modules\User\Models\Teacher;

class TeacherAssignment extends BaseAssignmentClassAbstract/* implements ManageAssignmentInterface*/
{

    private Teacher $teacher;

    public function __construct(Teacher $teacher)
    {
        $this->teacher = $teacher;
    }

    public function myAssignmentsQuery(){
        $myAssignmentsQuery = Assignment::query();

        $myAssignmentsQuery->where('teacher_id',$this->teacher->id);
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
