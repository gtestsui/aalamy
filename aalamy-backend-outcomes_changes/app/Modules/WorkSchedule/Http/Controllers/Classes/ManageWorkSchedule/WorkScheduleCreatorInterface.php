<?php


namespace Modules\WorkSchedule\Http\Controllers\Classes\ManageWorkSchedule;



use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Modules\Roster\Models\Roster;
use Modules\WorkSchedule\Models\WorkScheduleClass;

interface WorkScheduleCreatorInterface
{

    /**
     * @param $classId
     * @return Collection|WorkScheduleClass
     */
    public function getByClassId($classId);


}
