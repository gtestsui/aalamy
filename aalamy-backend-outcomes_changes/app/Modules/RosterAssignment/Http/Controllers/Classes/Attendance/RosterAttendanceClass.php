<?php


namespace Modules\RosterAssignment\Http\Controllers\Classes\Attendance;


use App\Exceptions\ErrorMsgException;
use App\Http\Controllers\Classes\FileSystemServicesClass;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use Modules\Roster\Models\Roster;
use Modules\Roster\Models\RosterStudent;
use Modules\RosterAssignment\Exports\RosterStudentsAttendanceExport;
use Modules\RosterAssignment\Exports\StudentAttendanceExport;
use Modules\RosterAssignment\Http\Controllers\Classes\ManageRosterAssignment\RosterAssignmentManagementFactory;
use Modules\RosterAssignment\Http\DTO\FilterRosterAssignmentAttendanceData;
use Modules\RosterAssignment\Models\RosterAssignment;
use Modules\RosterAssignment\Models\RosterAssignmentStudentAttendance;
use Modules\User\Models\User;

class RosterAttendanceClass extends BaseAttendanceAbstract
{


    protected $rosterAssignmentsIds;
    protected $roster;
//    protected $filterRosterAssignmentAttendanceData;

    public function __construct(Roster $roster,$user,?FilterRosterAssignmentAttendanceData $filterRosterAssignmentAttendanceData=null)
    {
        $request = Request();
//        $this->rosterAssignmentsIds = $rosterAssignmentsIds;
        $this->roster = $roster;
        $this->filterRosterAssignmentAttendanceData = $filterRosterAssignmentAttendanceData;

        $rosterAssignmentClass = RosterAssignmentManagementFactory::create($user);
        $this->rosterAssignmentsIds = $rosterAssignmentClass
            ->setFilter($filterRosterAssignmentAttendanceData->filter_roster_assignment_data)
            ->myRosterAssignmentsIdsByRosterId($this->roster->id);


    }

    /**
     * @return Builder
     */
    protected function query(){

        $rosterAssignmentStudentsAttendanceQuery = RosterAssignmentStudentAttendance::query()
            ->whereIn('roster_assignment_id',$this->rosterAssignmentsIds)
            ->filterByStudent($this->filterRosterAssignmentAttendanceData->student_id)
            ->with(['Student.User','RosterAssignment'=>function($query){
                return $query->with(['Roster','Assignment']);
            }])
//            ->with(['Student.User','RosterAssignment.Assignment'])
            ->orderBy('roster_assignment_id','asc');


        return $rosterAssignmentStudentsAttendanceQuery;
    }


    /**
     * @return string (inner path inside default storage path)
     */
    public function exportAsExcel(){
        $rosterAssignmentStudentsAttendances = $this->getAttendance()
            ->groupBy(['student_id','roster_assignment_id']);

        $rosterStudents = RosterStudent::where('roster_id',$this->roster->id)
            ->with(['ClassStudent.Student.User'])
            ->get();

        $rosterAssignments = RosterAssignment::where('roster_id',$this->roster->id)
            ->with('Assignment')
            ->get();

        $innerPath = $this->getExportedFilePath();

        Excel::store(new RosterStudentsAttendanceExport($rosterAssignmentStudentsAttendances,$rosterStudents,$rosterAssignments),
            $innerPath
        );
//        $path = FileSystemServicesClass::getDiskRoot()."/$innerPath";
        $path = FileSystemServicesClass::getDefaultStoragePathInsideDisk()."/$innerPath";

        return $path;

    }

    protected function getExportedFilePath(){
        $time = Carbon::now()->microsecond;
        $rosterName = $this->roster->name;
        $rosterName = str_replace(' ','-',$rosterName);

        $path = "$this->exportedExcelStoragePath/"
            .'roster-students/'
            ."$rosterName/"
            .'all-students/'
            ."$time/"
            ."$rosterName.xlsx";

        return $path;
    }


    /**
     * @return string
     */
    public function exportAsExcelForDefinedStudent(){
        $rosterAssignmentStudentsAttendances = $this->getAttendance();


        $innerPath = $this->getExportedFilePath($rosterAssignmentStudentsAttendances);

        Excel::store(new StudentAttendanceExport($rosterAssignmentStudentsAttendances),
            $innerPath
        );
//        $path = FileSystemServicesClass::getDiskRoot()."/$innerPath";
        $path = FileSystemServicesClass::getDefaultStoragePathInsideDisk()."/$innerPath";

        return $path;

    }

    protected function getExportedFilePathForDefinedStudent($rosterAssignmentStudentsAttendances){
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
    }



}
