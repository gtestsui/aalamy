<?php


namespace Modules\Assignment\Http\Controllers\Classes\ManageAssignment;


use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Modules\Assignment\Models\Assignment;

interface ManageAssignmentInterface
{
    /**
     * prepare the assignment query to use it in another functions
     * @return Builder
     */
    public function myAssignmentsQuery();

    /**
     * @return Collection of Assignment
     */
    public function myAssignments();

    /**
     * @return Assignment|null of Assignment
     */
    public function myAssignmentById($id);

    /**
     * @return Assignment of Assignment
     */
    public function myAssignmentByIdOrFail($id);

    /**
     * @param mixed $id
     * @note check if I have access on the assignment to display it
     * @return bool
     */
    public function checkCanShowAssignment($id);

}
