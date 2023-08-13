<?php


namespace Modules\Mark\Http\Controllers\Classes\GradeBookProcessor;


use App\Exceptions\ErrorMsgException;
use App\Http\Controllers\Classes\FileSystemServicesClass;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Modules\Mark\Exports\GradeBookExport;
use Modules\Mark\Http\Controllers\Classes\Mark\BaseMarkAbstract;
use Modules\Mark\Http\DTO\GradeBookData;
use Modules\Mark\Models\GradeBook;
use Modules\Mark\Models\GradeBookExternalMark;
use Modules\Mark\Models\GradeBookQuiz;
use Modules\Mark\Models\GradeBookRosterAssignment;
use Maatwebsite\Excel\Facades\Excel;
use Modules\RosterAssignment\Models\RosterAssignment;
use Modules\User\Models\Student;


class GradeBookClass extends BaseMarkAbstract
{


    protected $rosterId;
    protected $user;
    protected GradeBookData $gradeBookData;
    protected GradeBook $gradeBook;

    protected $rosterAssignmentsIds;
    protected $rosterAssignments;
    protected $students;
    protected $quizzes;

    protected GradeBookQuizClass $gradeBookQuizClass;
    protected GradeBookRosterAssignmentClass $gradeBookRosterAssignmentClass;
    protected GradeBookExternalMarkClass $gradeBookExternalMarkClass;

    public function __construct($rosterId,GradeBookData $gradeBookData,GradeBook $gradeBook)
    {
        $this->user = request()->user();

        $this->rosterId = $rosterId;
        $this->gradeBookData = $gradeBookData;
        $this->gradeBook = $gradeBook;


        $this->students = Student::whereHas('ClassStudents',function ($query)use($rosterId){
                return $query->whereHas('RosterStudents',function ($query)use($rosterId){
                    return $query->where('roster_id',$rosterId);
                });
            })
            ->with('User')
            ->get();

        if(!count($this->students))
            throw new ErrorMsgException('there is no students in this roster');

        return $this;



    }


    public function prepareRosterAssignments(){


        $gradeBookRosterAssignmentClass = new GradeBookRosterAssignmentClass(
            $this->rosterId,$this->gradeBookData,$this->gradeBook
        );
        list($this->rosterAssignmentsIds,$this->rosterAssignments) =
            $gradeBookRosterAssignmentClass->prepare();
        $this->gradeBookRosterAssignmentClass = $gradeBookRosterAssignmentClass;

        return $this;

    }

    public function prepareQuizzes(){

        $gradeBookQuizClass = new GradeBookQuizClass(
            $this->rosterId,$this->gradeBookData,$this->gradeBook
        );
        $this->quizzes = $gradeBookQuizClass->prepare();
        $this->gradeBookQuizClass = $gradeBookQuizClass;

        return $this;

    }

    public function prepareExternalMarks(){

        $gradeBookExternalMarkClass = new GradeBookExternalMarkClass(
            $this->gradeBookData,$this->gradeBook
        );
        $gradeBookExternalMarkClass->prepare();
        $this->gradeBookExternalMarkClass = $gradeBookExternalMarkClass;

        return $this;

    }

    /**
     * @note in the object student there is a filed marks(the key inside it is roster_assignment_id )
     * @return Collection|Student
     */
    protected function proccess()
    {
        $assignmentsWeights =  $this->gradeBookRosterAssignmentClass->createGradeBookRosterAssignments();
        $quizzesWeights = $this->gradeBookQuizClass->createGradeBookQuizzes();
        $sumWeights = $assignmentsWeights+$quizzesWeights+$this->gradeBookData->external_marks_weight;
        if($sumWeights != 100)
            throw new ErrorMsgException('the total weight should be 100');

        foreach ($this->students as $key => $student) {


            $finalGrade = 0;
            $rosterAssignmentsCount = 0;
            $quizzesCount = 0;
            $this->gradeBookRosterAssignmentClass->proccess($student,$finalGrade,$rosterAssignmentsCount);
            $this->gradeBookQuizClass->proccess($student,$finalGrade,$quizzesCount);

            $this->checkSelectedRosterAssignmentsAndQuizzesCount($rosterAssignmentsCount,$quizzesCount);

            $this->gradeBookExternalMarkClass->proccess($student,$finalGrade);

//            if($key==0){
//                GradeBookRosterAssignment::insert($this->gradeBookRosterAssignmentClass->getArrayForCreate());
//                GradeBookQuiz::insert($this->gradeBookQuizClass->getArrayForCreate());
//            }

            GradeBookExternalMark::insert($this->gradeBookExternalMarkClass->getArrayForCreate());

            $student->final_grade = $finalGrade;

        }

//        GradeBookRosterAssignment::insert($this->gradeBookRosterAssignmentClass->getArrayForCreate());
//        GradeBookQuiz::insert($this->gradeBookQuizClass->getArrayForCreate());
//        GradeBookExternalMark::insert($this->gradeBookExternalMarkClass->getArrayForCreate());

        return $this->students;
    }

    private function checkSelectedRosterAssignmentsAndQuizzesCount($rosterAssignmentsCount,$quizzesCount){
        $rosterAssignmentsAndQuizzesCount = $rosterAssignmentsCount+$quizzesCount;
        if($rosterAssignmentsAndQuizzesCount == 0)
            throw new ErrorMsgException('you dont have any quizzes or assignments has been matched');

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

        $thereAnExternalMarks = $this->gradeBookExternalMarkClass->thereAnExternalMarks;
        Excel::store(new GradeBookExport($this->students,
            $rosterAssignments
            ,$this->quizzes
            ,$thereAnExternalMarks),
            $innerPath
        );
        $path = FileSystemServicesClass::getDefaultStoragePathInsideDisk()."/$innerPath";

        return $path;

    }

    /**
     * @return array
     */
    public function getMarksAndExports(){
        $students = $this->proccess();

        $innerPath = $this->getExportedFilePath();

        $rosterAssignments = RosterAssignment::whereIn('id',$this->rosterAssignmentsIds)
            ->with('Assignment')
            ->get();
        $thereAnExternalMarks = $this->gradeBookExternalMarkClass->thereAnExternalMarks;

        Excel::store(new GradeBookExport($this->students,
            $rosterAssignments
            ,$this->quizzes
            ,$thereAnExternalMarks),
            $innerPath
        );
        $path = FileSystemServicesClass::getDefaultStoragePathInsideDisk()."/$innerPath";


        return [$students,$path,$rosterAssignments,$this->quizzes,$thereAnExternalMarks];

    }

    protected function getExportedFilePath(){
        $time = Carbon::now()->microsecond;
//        $roster = Roster::findOrFail($this->rosterId);
//        $rosterName = $roster->name;
//        $rosterName = str_replace(' ','-',$rosterName);


        $path = "$this->exportedExcelStoragePath/"
        ."grade-books/"
//        ."$rosterName/"
        .$this->gradeBookData->grade_book_name."/"
        ."$time/"
        .$this->gradeBookData->grade_book_name.".xlsx";

        return $path;
    }



}
