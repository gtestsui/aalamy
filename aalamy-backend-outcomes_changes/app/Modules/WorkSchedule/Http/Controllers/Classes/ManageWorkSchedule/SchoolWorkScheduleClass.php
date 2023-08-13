<?php


namespace Modules\WorkSchedule\Http\Controllers\Classes\ManageWorkSchedule;


use Modules\User\Models\School;
use Modules\WorkSchedule\Models\WorkScheduleClass;


class SchoolWorkScheduleClass implements WorkScheduleCreatorInterface
{

    private School $school;
    public function __construct(School $school)
    {
        $this->school = $school;
    }


    /**
     * @inheritDoc
     */
    public function getByClassId($classId){
        $workScheduleClasses = WorkScheduleClass::where('class_id',$classId)
            ->orderBy('week_day_id','asc')
            ->orderBy('start','asc')
            ->get();
        return $workScheduleClasses;
    }



}
