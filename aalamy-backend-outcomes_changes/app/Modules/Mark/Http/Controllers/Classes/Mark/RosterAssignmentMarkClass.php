<?php


namespace Modules\Mark\Http\Controllers\Classes\Mark;


use App\Http\Controllers\Classes\FileSystemServicesClass;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Modules\Mark\Exports\RosterAssignmentStudentsMarksExport;
use Modules\Mark\Http\Controllers\Classes\MarkServices;
use Modules\Mark\Models\MongoStudentAnswer;
use Maatwebsite\Excel\Facades\Excel;
use Modules\RosterAssignment\Models\RosterAssignmentStudentPage;
use Modules\Sticker\Models\StudentPageSticker;
use Modules\User\Models\Student;


class RosterAssignmentMarkClass extends BaseMarkAbstract
{


    protected $mongoStudentAnswers;
    protected $rosterAssignment;
    protected $studentPageStickers;
    protected $students;

    public function __construct($rosterAssignment)
    {
        $this->rosterAssignment = $rosterAssignment->load(['RosterAssignmentPages']);


        $this->mongoStudentAnswers = MongoStudentAnswer::where('roster_assignment_id',(string)$rosterAssignment->id)
            ->get();
//        $rosterAssignmentPageIds = $rosterAssignment->RosterAssignmentPages->pluck('id')->toArray();
//        $rosterAssignmentStudentPageIds = RosterAssignmentStudentPage::whereIn('roster_assignment_page_id',$rosterAssignmentPageIds)
//            ->pluck('id')->toArray();
        $this->studentPageStickers = StudentPageSticker::where('roster_assignment_id',$rosterAssignment->id)
            ->with('Sticker')
            ->get();

        $this->students = Student::whereHas('ClassStudents',function ($query)use($rosterAssignment){
                return $query->whereHas('RosterStudents',function ($query)use($rosterAssignment){
                    return $query->where('roster_id',$rosterAssignment->roster_id);
                });
            })
            ->with('User')
            ->get();



    }

    /**
     * @return Collection|Student
     */
    protected function proccess(){
        foreach ($this->students as $student){
            $student->roster_assignment_mark = 0;
            $student->full_mark = 0;
            $student->sticker_mark = 0;
            $definedStudentAnswers = $this->mongoStudentAnswers->where('student_id',$student->id)->all();


            $studentPageStickersForDefinedStudent = $this->studentPageStickers->where('student_id',$student->id)->all();
            foreach ($studentPageStickersForDefinedStudent as $studentPageSticker) {
                $student->full_mark+= $studentPageSticker->Sticker->mark;
                $student->sticker_mark+= $studentPageSticker->Sticker->mark;
            }

            if(empty($definedStudentAnswers)){
                continue;
            }

            foreach ($definedStudentAnswers as $answer){
                $questionMark = MarkServices::{$answer['question_type']}($answer['answer_body']);
                $student->roster_assignment_mark+=  $questionMark;
                $student->full_mark+=  $questionMark;
            }

        }
        return $this->students;
    }

    /**
     * @return string (inner path inside default storage path)
     */
    public function exportAsExcel(){
        $this->proccess();

        $innerPath = $this->getExportedFilePath($this->rosterAssignment);

        Excel::store(new RosterAssignmentStudentsMarksExport($this->rosterAssignment,$this->students),
            $innerPath
        );
        $path = FileSystemServicesClass::getDefaultStoragePathInsideDisk()."/$innerPath";

        return $path;

    }

    protected function getExportedFilePath($rosterAssignment){
        $time = Carbon::now()->microsecond;
        $assignmentName = $rosterAssignment->Assignment->name;
        $assignmentName = str_replace(' ','-',$assignmentName);


        $path = "$this->exportedExcelStoragePath/"
        .'roster-assignment-students/'
        ."$time/"
        ."$rosterAssignment->roster_id-$assignmentName.xlsx";

        return $path;
    }



}
