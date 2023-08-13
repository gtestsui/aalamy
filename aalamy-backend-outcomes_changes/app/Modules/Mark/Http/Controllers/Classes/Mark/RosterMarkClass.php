<?php


namespace Modules\Mark\Http\Controllers\Classes\Mark;


use App\Http\Controllers\Classes\FileSystemServicesClass;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Modules\Mark\Exports\RosterAssignmentStudentsMarksExport;
use Modules\Mark\Exports\RosterStudentsMarksExport;
use Modules\Mark\Http\Controllers\Classes\MarkServices;
use Modules\Mark\Models\MongoStudentAnswer;
use Maatwebsite\Excel\Facades\Excel;
use Modules\Roster\Models\Roster;
use Modules\RosterAssignment\Http\Controllers\Classes\ManageRosterAssignment\RosterAssignmentManagementFactory;
use Modules\RosterAssignment\Http\DTO\FilterRosterAssignmentData;
use Modules\RosterAssignment\Models\RosterAssignment;
use Modules\RosterAssignment\Models\RosterAssignmentStudentPage;
use Modules\Sticker\Models\StudentPageSticker;
use Modules\User\Models\Student;


class RosterMarkClass extends BaseMarkAbstract
{


    protected $mongoStudentAnswers;
    protected $rosterAssignmentIds;
    protected $studentPageStickers;
    protected $students;
    protected $roster;

    public function __construct($user,Roster $roster,?FilterRosterAssignmentData $filterRosterAssignmentData=null)
    {

        $this->roster = $roster;

        $rosterAssignmentClass = RosterAssignmentManagementFactory::create($user);
        $this->rosterAssignmentsIds =$rosterAssignmentClass
            ->setFilter($filterRosterAssignmentData)
            ->myRosterAssignmentsIdsByRosterId($roster->id);


        $this->studentPageStickers = StudentPageSticker::whereIn('roster_assignment_id',$this->rosterAssignmentsIds)
            ->with('Sticker')
            ->get();

        $this->students = Student::whereHas('ClassStudents',function ($query)use($roster){
                return $query->whereHas('RosterStudents',function ($query)use($roster){
                    return $query->where('roster_id',$roster->id);
                });
            })
            ->with('User')
            ->get();



    }

    /**
     * @note in the object student there is a filed marks(the key inside it is roster_assignment_id )
     * @return Collection|Student
     */
    protected function proccess()
    {
        $marks = [];
        foreach ($this->students as $student){
            $mongoStudentAnswers = MongoStudentAnswer::where('student_id',(string)$student->id)
                ->get();
            $student->marks = [];
            foreach ($this->rosterAssignmentsIds as $rosterAssignmentId) {
                $marks[$rosterAssignmentId] = [
                    'roster_assignment_mark' => 0,
                    'full_mark' => 0,
                    'sticker_mark' => 0,
                ];

                $definedStudentAnswers = $mongoStudentAnswers->where('roster_assignment_id', $rosterAssignmentId)
                    ->all();


                $studentPageStickersForDefinedStudent = $this->studentPageStickers
                    ->where('student_id', $student->id)
                    ->where('roster_assignment_id', $rosterAssignmentId)
                    ->all();
                foreach ($studentPageStickersForDefinedStudent as $studentPageSticker) {
                    $marks[$rosterAssignmentId]['full_mark'] += $studentPageSticker->Sticker->mark;
                    $marks[$rosterAssignmentId]['sticker_mark'] += $studentPageSticker->Sticker->mark;
                }

                if (empty($definedStudentAnswers)) {
                    continue;
                }

                foreach ($definedStudentAnswers as $answer) {
                    $questionMark = MarkServices::{$answer['question_type']}($answer['answer_body']);
                    $marks[$rosterAssignmentId]['roster_assignment_mark'] += $questionMark;
                    $marks[$rosterAssignmentId]['full_mark'] += $questionMark;
                }

            }
            $student->marks = $marks;
            $marks = [];
        }
        return $this->students;
    }

    /**
     * @return string (inner path inside default storage path)
     */
    public function exportAsExcel(){
        $this->proccess();

        $innerPath = $this->getExportedFilePath();

        $rosterAssignments = RosterAssignment::whereIn('id',$this->rosterAssignmentsIds)
            ->with('Assignment')
            ->get();

        Excel::store(new RosterStudentsMarksExport($this->students,$rosterAssignments),
            $innerPath
        );
        $path = FileSystemServicesClass::getDefaultStoragePathInsideDisk()."/$innerPath";

        return $path;

    }

    protected function getExportedFilePath(){
        $time = Carbon::now()->microsecond;
        $rosterName = $this->roster->name;
        $rosterName = str_replace(' ','-',$rosterName);


        $path = "$this->exportedExcelStoragePath/"
        ."roster-students/"
        ."$rosterName/"
        ."$time/"
        ."$rosterName.xlsx";

        return $path;
    }



}
