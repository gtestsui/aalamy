<?php


namespace Modules\WorkSchedule\Http\Controllers\Classes\ManageWorkSchedule;



use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Modules\Roster\Models\Roster;
use Modules\WorkSchedule\Models\WorkScheduleClass;

interface WorkScheduleReaderInterface
{


    /**
     * @return Builder|WorkScheduleClass of Roster
     */
    public function myQuery();

    /**
     * @return Collection|WorkScheduleClass
     */
    public function myWorkSchedule();

}
