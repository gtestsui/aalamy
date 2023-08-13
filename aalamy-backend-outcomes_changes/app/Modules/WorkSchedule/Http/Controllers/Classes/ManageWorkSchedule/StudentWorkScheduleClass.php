<?php


namespace Modules\WorkSchedule\Http\Controllers\Classes\ManageWorkSchedule;


use Modules\ClassModule\Http\Controllers\Classes\ManageClass\StudentClassManagement;
use Modules\Level\Http\Controllers\Classes\ManageLevel\SchoolLevel;
use Modules\User\Models\Student;
use Modules\WorkSchedule\Models\WorkScheduleClass;
use App\Exceptions\ErrorMsgException;


class StudentWorkScheduleClass  implements WorkScheduleReaderInterface
{

    private Student $student;
    public function __construct(Student $student)
    {
        $this->student = $student;
    }

    /**
     * @inheritDoc
     */
    public function myQuery(){

        $studentClassManagement = new StudentClassManagement($this->student);
        $this->student->load('SchoolStudent.School');
    	if(is_null($this->student->SchoolStudent)){
            throw new ErrorMsgException('you should belong to school to use this');
        }
        $schoolLevelClass = new SchoolLevel($this->student->SchoolStudent->School);
        $mySchoolLevelsIds = $schoolLevelClass->myLevels()->pluck('id')->toArray();
        $classId = $studentClassManagement->myClassesQuery()
            ->whereIn('level_id',$mySchoolLevelsIds)
            ->first()
            ->id;

        $workScheduleQuery = WorkScheduleClass::query()
            ->where('class_id',$classId)
            ->orderBy('week_day_id','asc')
            ->orderBy('start','asc')
            ->with(['ClassInfo'=>function($query){
                return $query->with([
                    'LevelSubject.Subject',
                    'Teacher.User'
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
