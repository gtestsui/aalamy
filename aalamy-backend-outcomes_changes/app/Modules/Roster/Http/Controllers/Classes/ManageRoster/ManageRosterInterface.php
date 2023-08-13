<?php


namespace Modules\Roster\Http\Controllers\Classes\ManageRoster;



use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Modules\Roster\Models\Roster;

interface ManageRosterInterface
{


    /**
     * @return Builder
     */
    public function myRostersQuery();

    /**
     * @return Collection of Roster
     */
    public function myRosters();

    /**
     * @param mixed $id
     * @return Roster of Roster
     */
    public function myRosterByIdOrFail($id);

    /**
     * @param mixed $id
     * @return Roster|null of Roster
     */
    public function myRosterById($id);

    /**
     * @param mixed $classId
     * @return Collection of Roster
     */
    public function allMyRostersByClassId($classId);

}
