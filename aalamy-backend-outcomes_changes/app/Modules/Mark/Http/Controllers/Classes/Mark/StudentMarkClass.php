<?php


namespace Modules\Mark\Http\Controllers\Classes\Mark;


use App\Http\Controllers\Classes\FileSystemServicesClass;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Maatwebsite\Excel\Facades\Excel;
use Modules\Mark\Exports\StudentMarkExport;
use Modules\Mark\Http\Controllers\Classes\MarkServices;
use Modules\Mark\Models\MongoSession;
use Modules\Mark\Models\MongoStudentAnswer;
use Modules\RosterAssignment\Http\Controllers\Classes\ManageRosterAssignment\RosterAssignmentManagementFactory;
use Modules\RosterAssignment\Http\DTO\FilterRosterAssignmentAttendanceData;
use Modules\RosterAssignment\Models\RosterAssignment;
use Modules\RosterAssignment\Models\RosterAssignmentStudentPage;
use Modules\Sticker\Models\StudentPageSticker;

class StudentMarkClass extends BaseMarkAbstract
{


    protected $rosterAssignments;
    protected $mongoStudentAnswers;
    protected $student;


    public function __construct($student,$user,?FilterRosterAssignmentAttendanceData $filterRosterAssignmentAttendanceData=null)
    {

        $this->student = $student;

        $rosterAssignmentClass = RosterAssignmentManagementFactory::create($user);
        $rosterAssignmentsIds =$rosterAssignmentClass
            ->setFilter($filterRosterAssignmentAttendanceData->filter_roster_assignment_data)
            ->myRosterAssignmentsIdsByMyRosters();

        $this->mongoStudentAnswers = MongoStudentAnswer::where('student_id',(string)$this->student->id)
            ->get();
        $this->rosterAssignments = RosterAssignment::with(['RosterAssignmentPages','Assignment'])
            ->whereIn('id',$rosterAssignmentsIds)->get();




    }

    /**
     * @return Collection|RosterAssignment
     */
    protected function proccess(){

        foreach ($this->rosterAssignments as $rosterAssignment){
            $rosterAssignment->roster_assignment_original_mark = $this->calculateRosterAssignmentFullMark($rosterAssignment);
            $rosterAssignment->roster_assignment_mark = 0;
            $rosterAssignment->full_mark = 0;
            $rosterAssignment->sticker_mark = 0;

            $rosterAssignmentStudentAnswers = $this->mongoStudentAnswers
                ->where('roster_assignment_id',$rosterAssignment->id)
                ->all();

            $rosterAssignmentPageIds = $rosterAssignment->RosterAssignmentPages->pluck('id')->toArray();
            $rosterAssignmentStudentPageIds = RosterAssignmentStudentPage::whereIn('roster_assignment_page_id',$rosterAssignmentPageIds)
                ->where('student_id',$this->student->id)
                ->pluck('id')->toArray();
            $studentPageStickers = StudentPageSticker::whereIn('roster_assignment_student_page_id',$rosterAssignmentStudentPageIds)
                ->where('student_id',$this->student->id)
                ->with('Sticker')
                ->get();

            foreach ($studentPageStickers as $studentPageSticker) {
                $rosterAssignment->full_mark+= $studentPageSticker->Sticker->mark;
                $rosterAssignment->sticker_mark+= $studentPageSticker->Sticker->mark;
            }

            if(empty($rosterAssignmentStudentAnswers)){
                continue;
            }

            foreach ($rosterAssignmentStudentAnswers as $answer){
                $questionMark = MarkServices::{$answer['question_type']}($answer['answer_body']);
                $rosterAssignment->roster_assignment_mark+=  $questionMark;
                $rosterAssignment->full_mark+=  $questionMark;
            }

        }
        return  $this->rosterAssignments;
    }

    /**
     * @param RosterAssignment $rosterAssignment
     * @return float|int
     */
    private function calculateRosterAssignmentFullMark($rosterAssignment){
        return calculateRosterAssignmentFullMark($rosterAssignment);
    }

    /**
     * @return string (inner path inside default storage path)
     */
    public function exportAsExcel(){
        $this->proccess();

        $innerPath = $this->getExportedFilePath($this->student);


        Excel::store(new StudentMarkExport($this->student,$this->rosterAssignments),
            $innerPath
        );
//        $path = FileSystemServicesClass::getDiskRoot()."/$innerPath";
        $path = FileSystemServicesClass::getDefaultStoragePathInsideDisk()."/$innerPath";

        return $path;

    }

    protected function getExportedFilePath($student){
        $time = Carbon::now()->microsecond;
        $studentName = $student->User->getFullName('-');
        $path = "$this->exportedExcelStoragePath/"
            .'students/'
            ."$studentName/"
            ."$time/"
            ."$studentName.xlsx";



        return $path;
    }


}
