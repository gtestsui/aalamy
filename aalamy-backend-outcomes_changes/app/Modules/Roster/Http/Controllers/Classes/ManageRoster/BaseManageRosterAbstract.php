<?php


namespace Modules\Roster\Http\Controllers\Classes\ManageRoster;



use Illuminate\Database\Eloquent\Collection;

abstract class BaseManageRosterAbstract
{

    /**
     * @return Collection of Roster
     */
    public function myRosters(){
        $myRosters = $this->myRostersQuery()->get();
        return $myRosters;
    }

    public function myRosterByIdQuery($id){
        return $this->myRostersQuery()->where('id',$id);
    }


    public function myRosterById($id){
        return $this->myRosterByIdQuery($id)->first();
    }

    public function myRosterByIdOrFail($id){
        return $this->myRosterByIdQuery($id)->firstOrFail();
    }

    /**
     * @return Collection of Roster
     */
    public function myRosterByIds($ids){
        return $this->myRostersQuery()->whereIn('id',$ids)->get();
    }

}
