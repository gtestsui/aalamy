<?php


namespace Modules\Assignment\Http\Controllers\Classes\ManageAssignmentPage;


use Illuminate\Database\Eloquent\Collection;
use Modules\Assignment\Http\Controllers\Classes\ManageAssignment\SchoolAssignment;
use Modules\Assignment\Models\Page;
use Modules\RosterAssignment\models\RosterAssignment;
use Modules\Roster\Http\Controllers\Classes\ManageRoster\SchoolRosterClass;
use Modules\User\Models\School;

class SchoolAssignmentPage extends BaseAssignmentPageAbstract
{

    private School $school;

    public function __construct(School $school)
    {
        $this->school = $school;
    }

    protected function myAssignmentsPagesQuery(){
        $assignmentClass = new SchoolAssignment($this->school);
        $myAssignmentsIds = $assignmentClass->myAssignmentsIds();
        $myAssignmentsPagesQuery = Page::query()
            ->whereIn('assignment_id',$myAssignmentsIds);

        return $myAssignmentsPagesQuery;
    }


    protected function myAssignmentPagesByAssignmentIdQuery($assignmentId){
        $assignmentClass = new SchoolAssignment($this->school);
        $myAssignment = $assignmentClass->myAssignmentByIdOrFail($assignmentId);
        $myAssignmentsPagesQuery = Page::query()
            ->where('assignment_id',$myAssignment->id);

        return $myAssignmentsPagesQuery;
    }

}
