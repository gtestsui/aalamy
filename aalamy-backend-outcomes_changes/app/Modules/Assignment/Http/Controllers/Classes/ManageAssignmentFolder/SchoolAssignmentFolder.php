<?php


namespace Modules\Assignment\Http\Controllers\Classes\ManageAssignmentFolder;


use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;
use Modules\Assignment\Models\Assignment;
use Modules\Assignment\Models\AssignmentFolder;
use Modules\User\Models\School;

class SchoolAssignmentFolder extends BaseAssignmentFolderClassAbstract
{

    private School $school;

    public function __construct(School $school)
    {
        $this->school = $school;
    }

    public function myAssignmentFoldersQuery(){
        $myAssignmentsQuery = AssignmentFolder::query();

        //when the teacher store assignment we store school_id and we do that we the school store assignment too
        //so all assignments in school its have school_if
        $myAssignmentsQuery->where('school_id',$this->school->id);
        return $myAssignmentsQuery;
    }



}
