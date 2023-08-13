<?php


namespace Modules\Assignment\Http\Controllers\Classes\ManageAssignmentFolder;


use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Pagination\LengthAwarePaginator;
use Modules\Assignment\Models\Assignment;
use Modules\Assignment\Models\AssignmentFolder;

abstract class BaseAssignmentFolderClassAbstract
{

    abstract public function myAssignmentFoldersQuery();

    public function myAssignmentFolders():Collection
    {
        $myAssignmentFoldersQuery = $this->myAssignmentFoldersQuery();
        $myAssignments = $myAssignmentFoldersQuery->get();
        return $myAssignments;
    }

    public function myRootAssignmentFoldersPaginate():LengthAwarePaginator
    {
        $myAssignmentFoldersQuery = $this->myAssignmentFoldersQuery();
        $myAssignments = $myAssignmentFoldersQuery
            ->isRoot()
            ->paginate(100);
        return $myAssignments;
    }


    public function myAssignmentFoldersPaginate():LengthAwarePaginator
    {
        $myAssignmentFoldersQuery = $this->myAssignmentFoldersQuery();
        $myAssignments = $myAssignmentFoldersQuery->paginate(100);
        return $myAssignments;
    }

    public function myAssignmentFoldersIds():array
    {
        $myAssignmentFoldersQuery = $this->myAssignmentFoldersQuery();
        $ids = $myAssignmentFoldersQuery->pluck('id')->toArray();
        return $ids;
    }


    /**
     * @return Builder
     */
    public function myAssignmentFolderByIdQuery($id): Builder
    {
        $myAssignmentFoldersQuery = $this->myAssignmentFoldersQuery()->where('id',$id);
        return $myAssignmentFoldersQuery;
    }

    /**
     * @return Builder|AssignmentFolder|null
     */
    public function myAssignmentFolderById($id): ?AssignmentFolder
    {
        $myAssignmentFolder = $this->myAssignmentFolderByIdQuery($id)->first();
        return $myAssignmentFolder;
    }

    /**
     * @return AssignmentFolder|Builder
     */
    public function myAssignmentFolderByIdOrFail($id): AssignmentFolder
    {
        $myAssignmentFolder = $this->myAssignmentFolderByIdQuery($id)->firstOrFail();
        return $myAssignmentFolder;
    }





}
