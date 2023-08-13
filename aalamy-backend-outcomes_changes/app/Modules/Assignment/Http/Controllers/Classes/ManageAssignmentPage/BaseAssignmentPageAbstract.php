<?php


namespace Modules\Assignment\Http\Controllers\Classes\ManageAssignmentPage;


use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Modules\RosterAssignment\models\RosterAssignment;

abstract class BaseAssignmentPageAbstract
{

    /**
     * @return Builder
     */
    abstract protected function myAssignmentsPagesQuery();
    /**
     * @return Builder
     */
    abstract protected function myAssignmentPagesByAssignmentIdQuery($assignmentId);




    protected function getMyAssignmentsPageByIdQuery($id){
        return $this->myAssignmentsPagesQuery()->where('id',$id);
    }

    public function getMyAssignmentsPageById($id){
        $assignmentPage = $this->getMyAssignmentsPageByIdQuery($id)->first();
        return $assignmentPage;
    }

    public function getMyAssignmentsPageByIdOrFail($id){
        $assignmentPage = $this->getMyAssignmentsPageByIdQuery($id)->firstOrFail();
        return $assignmentPage;
    }


    protected function getMyAssignmentPageByAssignmentIdByPageIdQuery($assignmentId,$pageId){
        return $this->myAssignmentPagesByAssignmentIdQuery($assignmentId)->where('id',$pageId);
    }

    public function getMyAssignmentPageByAssignmentIdByPageId($assignmentId,$pageId){
        $assignmentPage = $this->getMyAssignmentPageByAssignmentIdByPageIdQuery($assignmentId,$pageId)->first();
        return $assignmentPage;
    }

    public function getMyAssignmentPageByAssignmentIdByPageIdOrFail($assignmentId,$pageId){
        $assignmentPage = $this->getMyAssignmentPageByAssignmentIdByPageIdQuery($assignmentId,$pageId)->firstOrFail();
        return $assignmentPage;
    }


}
