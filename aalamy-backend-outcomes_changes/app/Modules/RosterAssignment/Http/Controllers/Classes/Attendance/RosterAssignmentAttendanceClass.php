<?php


namespace Modules\RosterAssignment\Http\Controllers\Classes\Attendance;


use App\Http\Controllers\Classes\FileSystemServicesClass;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Modules\RosterAssignment\Http\DTO\FilterRosterAssignmentAttendanceData;
use Modules\RosterAssignment\Models\RosterAssignmentStudentAttendance;
use Maatwebsite\Excel\Facades\Excel;
use Modules\RosterAssignment\Exports\StudentAttendanceExport;


class RosterAssignmentAttendanceClass extends BaseAttendanceAbstract
{


    protected $rosterAssignmentId;

    public function __construct($rosterAssignmentId)
    {
        $this->rosterAssignmentId = $rosterAssignmentId;


    }

    /**
     * @return Builder
     */
    protected function query(){
        $rosterAssignmentStudentsAttendanceQuery = RosterAssignmentStudentAttendance::query()
            ->where('roster_assignment_id',$this->rosterAssignmentId)
            ->with(['Student.User','RosterAssignment'=>function($query){
                return $query->with(['Roster','Assignment']);
            }])
//            ->with(['Student.User','RosterAssignment'])
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
        $rosterName = $rosterAssignmentStudentsAttendances[0]->RosterAssignment->Roster->name;
        $rosterName = str_replace(' ','-',$rosterName);

        $assignmentName = $rosterAssignmentStudentsAttendances[0]->RosterAssignment->Assignment->name;
        $assignmentName = str_replace(' ','-',$assignmentName);


        $path = "$this->exportedExcelStoragePath/"
        .'roster-assignment-students/'
        ."$time/"
        ."$rosterName-$assignmentName.xlsx";

        return $path;
    }



}
