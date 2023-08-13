<?php


namespace Modules\Meeting\Http\Controllers\Classes\ManageMeeting\Attendance;


use App\Exceptions\ErrorMsgException;
use App\Http\Controllers\Classes\FileSystemServicesClass;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Maatwebsite\Excel\Facades\Excel;
use Modules\Meeting\Http\Controllers\Classes\ManageMeeting\MeetingOwner\MeetingOwnerManagementFactory;
use Modules\RosterAssignment\Exports\StudentAttendanceExport;
use Modules\RosterAssignment\Http\Controllers\Classes\ManageRosterAssignment\RosterAssignmentManagementFactory;
use Modules\RosterAssignment\Http\DTO\FilterRosterAssignmentAttendanceData;
use Modules\RosterAssignment\Models\RosterAssignmentStudentAttendance;
use Modules\User\Models\Student;

class StudentMeetingsAttendanceClass extends BaseAttendanceAbstract
{


    protected $rosterAssignmentsIds;
    protected $studentId;
    protected $user;
    protected $start_date=null;
    protected $end_date=null;
    protected ?array $meetingsIds;


    public function __construct($studentId,$user,$startDate,$endDate,$meetingsIds)
    {
        $this->studentId = $studentId;
        $this->user = $user;
        $this->startDate = $startDate;
        $this->endDate = $endDate;
        $this->meetingsIds = $meetingsIds;




    }

    /**
     * @return Builder
     */
    protected function query(){

        $meetingManagementClass = MeetingOwnerManagementFactory::create($this->user);
        $meetingsQuery = $meetingManagementClass->myMeetingsQuery()
            ->whereHas('TargetUsers',function ($query){
                return $query->where('student_id',$this->studentId);
            })
            ->with(['TargetUsers' => function ($query){
                return $query->where('student_id',$this->studentId)->with('Student.User');
            }])
            ->when(isset($this->startDate),function ($query){
                return $query->where('date_time','>=',$this->startDate);
            })
            ->when(isset($this->endDate),function ($query){
                return $query->where('date_time','<=',$this->endDate);
            })
            ->when(isset($this->meetingsIds),function ($query){
                return $query->whereIn('id',$this->meetingsIds);
            });


        return $meetingsQuery;
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
        $path = FileSystemServicesClass::getDefaultStoragePathInsideDisk()."/$innerPath";

        return $path;

    }

    protected function getExportedFilePath($rosterAssignmentStudentsAttendances){
        $student = Student::with('User')->find($this->studentId);
        $studentName  = str_replace(' ','-',$student->User->getFullName());
        $time = (Carbon::now())->setTimezone(request()->time_zone)->microsecond;
        $fromDate = isset($this->startDate)?$this->startDate:null;
        $toDate = isset($this->endDate)?$this->endDate:$time;

        $path = "student-attendances/"
            .'students/'
            ."$studentName/"
            .'meetings/'
            ."$time/"
            ."$studentName-$fromDate-$toDate.xlsx";



        return $path;
    }


}
