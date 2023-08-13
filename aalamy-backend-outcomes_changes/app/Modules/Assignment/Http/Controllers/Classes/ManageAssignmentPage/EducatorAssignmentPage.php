<?php


namespace Modules\Assignment\Http\Controllers\Classes\ManageAssignmentPage;


use App\Exceptions\ErrorUnAuthorizationException;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Modules\Assignment\Http\Controllers\Classes\ManageAssignment\EducatorAssignment;
use Modules\Assignment\Models\Page;
use Modules\RosterAssignment\models\RosterAssignment;
use Modules\Roster\Http\Controllers\Classes\ManageRoster\EducatorRosterClass;
use Modules\User\Models\Educator;

class EducatorAssignmentPage extends BaseAssignmentPageAbstract
{

    private Educator $educator;

    public function __construct(Educator $educator)
    {
        $this->educator = $educator;
    }


    protected function myAssignmentsPagesQuery(){
        $assignmentClass = new EducatorAssignment($this->educator);
        $myAssignmentsIds = $assignmentClass->myAssignmentsIds();
        $myAssignmentsPagesQuery = Page::query()
            ->whereIn('assignment_id',$myAssignmentsIds);
        return $myAssignmentsPagesQuery;
    }

    protected function myAssignmentPagesByAssignmentIdQuery($assignmentId){
        $assignmentClass = new EducatorAssignment($this->educator);
        $myAssignment = $assignmentClass->myAssignmentByIdOrFail($assignmentId);
        $myAssignmentsPagesQuery = Page::query()
            ->where('assignment_id',$myAssignment->id);

        return $myAssignmentsPagesQuery;
    }



}
