<?php


namespace Modules\Roster\Http\Controllers\Classes\ManageRoster;



use Illuminate\Database\Eloquent\Collection;
use Modules\Roster\Models\Roster;

interface ManageRosterOwnerInterface
{


    /**
     * @return Collection of Roster
     */
    public function myRostersDoesntLinkedToAssignment($assignmentId);

    /**
     * @return Roster|Collection
     */
    public function myRostersByLevelSubjectId($levelSubjectId);

}
