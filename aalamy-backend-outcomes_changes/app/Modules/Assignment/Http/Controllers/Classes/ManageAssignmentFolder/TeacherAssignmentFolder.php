<?php


namespace Modules\Assignment\Http\Controllers\Classes\ManageAssignmentFolder;


use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;
use Modules\Assignment\Models\Assignment;
use Modules\Assignment\Models\AssignmentFolder;
use Modules\User\Models\Teacher;

class TeacherAssignmentFolder extends BaseAssignmentFolderClassAbstract
{

    private Teacher $teacher;

    public function __construct(Teacher $teacher)
    {
        $this->teacher = $teacher;
    }

    public function myAssignmentFoldersQuery(){
        $myAssignmentsQuery = AssignmentFolder::query();

        $myAssignmentsQuery->where('teacher_id',$this->teacher->id);
        return $myAssignmentsQuery;
    }



}
