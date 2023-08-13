<?php


namespace Modules\Meeting\Http\Controllers\Classes\ManageMeeting\Attendance;


use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;
use Modules\RosterAssignment\Http\DTO\FilterRosterAssignmentAttendanceData;

abstract class BaseAttendanceAbstract
{

    protected $exportedExcelStoragePath ='student-attendances';
    protected ?FilterRosterAssignmentAttendanceData $filterRosterAssignmentAttendanceData =null;

    /**
     * @return Builder
     */
    abstract protected function query();

    /**
     * @return string
     */
    abstract protected function exportAsExcel();


    public function setFilter(FilterRosterAssignmentAttendanceData $filterRosterAssignmentAttendanceData){
        $this->filterRosterAssignmentAttendanceData = $filterRosterAssignmentAttendanceData;
        return $this;
    }

    /**
     * @return Builder[]|Collection
     */
    public function getAttendance(){
        return $this->query()->get();
    }


    /**
     * @return LengthAwarePaginator
     */
    public function getAttendancePaginate($num=10){
        return $this->query()->paginate($num);
    }


}
