<?php


namespace Modules\Mark\Http\Controllers\Classes\Mark;


use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;
use Modules\RosterAssignment\Http\DTO\FilterRosterAssignmentAttendanceData;
use Modules\RosterAssignment\Models\RosterAssignment;
use Modules\User\Models\Student;

abstract class BaseMarkAbstract
{

    protected $exportedExcelStoragePath ='student-marks';
    protected ?FilterRosterAssignmentAttendanceData $filterRosterAssignmentAttendanceData =null;

    /**
     * @return Collection|Student|RosterAssignment
     */
    abstract protected function proccess();

    /**
     * @return string
     */
    abstract protected function exportAsExcel();


    public function setFilter(FilterRosterAssignmentAttendanceData $filterRosterAssignmentAttendanceData){
        $this->filterRosterAssignmentAttendanceData = $filterRosterAssignmentAttendanceData;
        return $this;
    }

    /**
     * @return Collection|Student|RosterAssignment
     */
    public function getMarks(){
        return $this->proccess();
    }


//
//
//    /**
//     * @return LengthAwarePaginator
//     */
//    public function getAttendancePaginate($num=10){
//        return $this->query()->paginate($num);
//    }


}
