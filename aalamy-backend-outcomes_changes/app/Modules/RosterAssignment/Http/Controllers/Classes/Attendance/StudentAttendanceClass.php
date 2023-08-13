<?php


namespace Modules\RosterAssignment\Http\Controllers\Classes\Attendance;


use App\Exceptions\ErrorMsgException;
use App\Http\Controllers\Classes\FileSystemServicesClass;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Maatwebsite\Excel\Facades\Excel;
use Modules\RosterAssignment\Exports\StudentAttendanceExport;
use Modules\RosterAssignment\Http\Controllers\Classes\ManageRosterAssignment\RosterAssignmentManagementFactory;
use Modules\RosterAssignment\Http\DTO\FilterRosterAssignmentAttendanceData;
use Modules\RosterAssignment\Models\RosterAssignmentStudentAttendance;

class StudentAttendanceClass extends BaseAttendanceAbstract
{


    protected $rosterAssignmentsIds;
    protected $studentId;
//    protected $filterRosterAssignmentAttendanceData;


    public function __construct($studentId,$user,?FilterRosterAssignmentAttendanceData $filterRosterAssignmentAttendanceData=null)
    {
        $this->studentId = $studentId;
        $this->filterRosterAssignmentAttendanceData = $filterRosterAssignmentAttendanceData;

        $rosterAssignmentClass = RosterAssignmentManagementFactory::create($user);
        $this->rosterAssignmentsIds =$rosterAssignmentClass
            ->setFilter($filterRosterAssignmentAttendanceData->filter_roster_assignment_data)
            ->myRosterAssignmentsIdsByMyRosters();


    }

    /**
     * @return Builder
     */
    protected function query(){

        $rosterAssignmentStudentsAttendanceQuery = RosterAssignmentStudentAttendance::query()
            ->filterByStudent($this->studentId)
            ->whereIn('roster_assignment_id',$this->rosterAssignmentsIds)
            ->with(['Student.User','RosterAssignment'=>function($query){
                return $query->with(['Roster','Assignment']);
            }])
            ->orderBy('roster_assignment_id','asc');


        return $rosterAssignmentStudentsAttendanceQuery;
    }


    /**
     * @return string (inner path inside default storage path)
     */
    public function exportAsExcel(){
        $rosterAssignmentStudentsAttendances = $this->getAttendance();


        $innerPath = $this->getExportedFilePath($rosterAssignmentStudentsAttendances);

        Excel::store(new StudentAttendanceExport($rosterAssignmentStudentsAttendances),
            $innerPath
        );
//        $path = FileSystemServicesClass::getDiskRoot()."/$innerPath";
        $path = FileSystemServicesClass::getDefaultStoragePathInsideDisk()."/$innerPath";

        return $path;

    }

    protected function getExportedFilePath($rosterAssignmentStudentsAttendances){
        $time = Carbon::now()->microsecond;
        $studentName = $rosterAssignmentStudentsAttendances[0]->Student->User->getFullName('-');
        $path = "$this->exportedExcelStoragePath/"
            .'students/'
            ."$studentName/"
            ."$time/"
            ."$studentName.xlsx";



        return $path;
    }


}
