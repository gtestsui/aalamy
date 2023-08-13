<?php


namespace Modules\Assignment\Http\Controllers\Classes\ManageAssignment;


use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Pagination\LengthAwarePaginator;
use Modules\Assignment\Models\Assignment;

abstract class BaseAssignmentClassAbstract implements ManageAssignmentInterface
{

    abstract public function myAssignmentsQuery();

    public function myAssignments():Collection
    {
        $myAssignmentsQuery = $this->myAssignmentsQuery();
        $myAssignments = $myAssignmentsQuery->get();
        return $myAssignments;
    }

    public function myAssignmentsIds():array
    {
        $myAssignmentsQuery = $this->myAssignmentsQuery();
        $myAssignmentsIds = $myAssignmentsQuery->pluck('id')->toArray();
        return $myAssignmentsIds;
    }

    /**
     * @return LengthAwarePaginator
     */
    public function myAssignmentsWithPagesCount():LengthAwarePaginator
    {
        $myAssignmentsQuery = $this->myAssignmentsQuery()
            ->search(request()->key,[],[
                'Unit',
                'Lesson',
                'LevelSubject'=>['Level','Subject'],
            ])
            ->withCount('Pages');
        $myAssignments = $myAssignmentsQuery->paginate(config('panel.Assignment.my_assignments_page_num'));
        return $myAssignments;
    }

    /**
     * @return LengthAwarePaginator
     */
    public function myAssignmentsWithPagesCountForAdmin($soft_delete):LengthAwarePaginator
    {
        $myAssignmentsQuery = $this->myAssignmentsQuery()
            ->trashed($soft_delete)
            ->search(request()->key,[],[
                'Unit',
                'Lesson',
                'LevelSubject'=>['Level','Subject'],
            ])
            ->withCount('Pages');
        $myAssignments = $myAssignmentsQuery->paginate(config('panel.Assignment.my_assignments_page_num'));
        return $myAssignments;
    }

    /**
     * @return Builder
     */
    public function myAssignmentByIdQuery($id): Builder
    {
        $myAssignmentsQuery = $this->myAssignmentsQuery()->where('id',$id);
        return $myAssignmentsQuery;
    }

    /**
     * @return Assignment|null
     */
    public function myAssignmentById($id): ?Assignment
    {
        $myAssignment= $this->myAssignmentByIdQuery($id)->first();
        return $myAssignment;
    }

    /**
     * @return Assignment
     */
    public function myAssignmentByIdOrFail($id): Assignment
    {
//        $myAssignment= $this->myAssignmentByIdQuery($id)->findOrFail($id);
        $myAssignment= $this->myAssignmentByIdQuery($id)->firstOrFail();
        return $myAssignment;
    }

    /**
     * @return Assignment
     */
    public function myAssignmentByIdWithPages($id): Assignment
    {
        $myAssignment= $this->myAssignmentByIdOrFail($id)
            ->load([
                'Pages','LevelSubject'=>function($query){
                    return $query->with(['Level','Subject']);
                },'Unit','Lesson'
            ]);

        return $myAssignment;
    }


    /**
     * @param mixed $id
     * @note check if I have access on the assignment to display it
     * @return bool
     */
    public function checkCanShowAssignment($id){
        $assignment = $this->myAssignmentById($id);
        if(!is_null($assignment)){
            return true;

        }
        return false;

    }



}
