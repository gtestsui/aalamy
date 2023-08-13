<?php


namespace Modules\WorkSchedule\Http\Controllers\Classes\ManageWorkSchedule;


use Modules\User\Models\Teacher;
use Modules\WorkSchedule\Models\WorkScheduleClass;

class TeacherWorkScheduleClass implements WorkScheduleReaderInterface
{

    private Teacher $teacher;
    public function __construct(Teacher $teacher)
    {
        $this->teacher = $teacher;
    }

    /**
     * @inheritDoc
     */
    public function myQuery(){

        $workScheduleQuery = WorkScheduleClass::query()
                ->whereHas('ClassInfo',function ($query){
                return $query->where('teacher_id',$this->teacher->id);
            })
            ->orderBy('week_day_id','asc')
            ->orderBy('start','asc')
            ->with(['ClassInfo'=>function($query){
                return $query->with([
                    'LevelSubject.Level',
                    'LevelSubject.Subject',
                    'ClassModel'
                ]);
            }]);

        return $workScheduleQuery;

    }

    /**
     * @inheritDoc
     */
    public function myWorkSchedule(){
        return $this->myQuery()->get();
    }

}
