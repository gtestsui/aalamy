<?php


namespace Modules\Meeting\Http\Controllers\Classes\ManageMeeting\Attendance;


use App\Exceptions\ErrorMsgException;
use App\Http\Controllers\Classes\FileSystemServicesClass;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use Modules\Meeting\Exports\MeetingAttendanceExport;
use Modules\Meeting\Models\Meeting;
use Modules\Roster\Models\Roster;
use Modules\Roster\Models\RosterStudent;
use Modules\RosterAssignment\Exports\RosterStudentsAttendanceExport;
use Modules\RosterAssignment\Exports\StudentAttendanceExport;
use Modules\RosterAssignment\Http\Controllers\Classes\ManageRosterAssignment\RosterAssignmentManagementFactory;
use Modules\RosterAssignment\Http\DTO\FilterRosterAssignmentAttendanceData;
use Modules\RosterAssignment\Models\RosterAssignment;
use Modules\RosterAssignment\Models\RosterAssignmentStudentAttendance;
use Modules\User\Models\User;

class MeetingAttendanceClass extends BaseAttendanceAbstract
{


    protected $meeting;

    public function __construct(Meeting $meeting)
    {

        $this->meeting = $meeting;

    }

    /**
     * @return Builder
     */
    protected function query(){

    }


    /**
     * @return string (inner path inside default storage path)
     */
    public function exportAsExcel(){

        $innerPath = $this->getExportedFilePath();

        Excel::store(new MeetingAttendanceExport($this->meeting),
            $innerPath
        );

        $path = FileSystemServicesClass::getDefaultStoragePathInsideDisk()."/$innerPath";

        return $path;

    }

    protected function getExportedFilePath(){
        $meetingName  = str_replace(' ','-',$this->meeting->title);
        $time = (Carbon::now())->setTimezone(request()->time_zone)->microsecond;
//        $time = Carbon::now()->microsecond;

        $path = "student-attendances/"
            .'meeting/'
            ."$meetingName/"
            .'all-students/'
            ."$time/"
            ."$meetingName-$time.xlsx";

        return $path;
    }


//     /**
//     * @return string
//     */
//    public function exportAsExcelForDefinedStudent(){
//        $rosterAssignmentStudentsAttendances = $this->getAttendance();
//
//
//        $innerPath = $this->getExportedFilePath($rosterAssignmentStudentsAttendances);
//
//        Excel::store(new StudentAttendanceExport($rosterAssignmentStudentsAttendances),
//            $innerPath
//        );
////        $path = FileSystemServicesClass::getDiskRoot()."/$innerPath";
//        $path = FileSystemServicesClass::getDefaultStoragePathInsideDisk()."/$innerPath";
//
//        return $path;
//
//    }

   /* protected function getExportedFilePathForDefinedStudent($rosterAssignmentStudentsAttendances){
        $time = Carbon::now()->microsecond;
        $rosterName = $this->roster->name;
        $rosterName = str_replace(' ','-',$rosterName);

        if(isset($this->filterRosterAssignmentAttendanceData->student_id)){
            $studentName = $rosterAssignmentStudentsAttendances[0]->Student->User->getFullName('-');
            $path = "$this->exportedExcelStoragePath/"
                .'roster-students/'
                ."$rosterName/"
                .'single-student/'
                ."$studentName/"
                ."$time/"
                ."$rosterName-$studentName.xlsx";
        }


        return $path;
    }*/



}
