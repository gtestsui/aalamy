<?php


namespace Modules\Assignment\Http\Controllers\Classes\ManageAssignmentFolder;


use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Pagination\LengthAwarePaginator;
use Modules\Assignment\Models\Assignment;
use Modules\Assignment\Models\AssignmentFolder;
use Modules\User\Models\Educator;

class EducatorAssignmentFolder extends BaseAssignmentFolderClassAbstract
{

    private Educator $educator;

    public function __construct(Educator $educator)
    {
        $this->educator = $educator;
    }

    public function myAssignmentFoldersQuery(){
        $myAssignmentsQuery = AssignmentFolder::query();

        $myAssignmentsQuery->where('educator_id',$this->educator->id);
        return $myAssignmentsQuery;
    }


}
