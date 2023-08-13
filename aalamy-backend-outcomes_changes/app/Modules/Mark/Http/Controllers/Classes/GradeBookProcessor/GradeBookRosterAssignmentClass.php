<?php


namespace Modules\Mark\Http\Controllers\Classes\GradeBookProcessor;


use App\Exceptions\ErrorMsgException;
use App\Http\Controllers\Classes\FileSystemServicesClass;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;
use Modules\Mark\Exports\GradeBookExport;
use Modules\Mark\Http\Controllers\Classes\Mark\BaseMarkAbstract;
use Modules\Mark\Http\Controllers\Classes\MarkServices;
use Modules\Mark\Http\DTO\GradeBookData;
use Modules\Mark\Models\GradeBook;
use Modules\Mark\Models\GradeBookRosterAssignment;
use Modules\Mark\Models\MongoSession;
use Modules\Mark\Models\MongoStudentAnswer;
use Maatwebsite\Excel\Facades\Excel;
use Modules\Quiz\Http\Controllers\Classes\ManageQuiz\QuizManagementFactory;
use Modules\Quiz\Models\Quiz;
use Modules\RosterAssignment\Http\Controllers\Classes\ManageRosterAssignment\RosterAssignmentManagementFactory;
use Modules\RosterAssignment\Http\DTO\FilterRosterAssignmentData;
use Modules\RosterAssignment\Models\RosterAssignment;
use Modules\Sticker\Models\StudentPageSticker;
use Modules\User\Models\Student;
use Modules\User\Models\User;


class GradeBookRosterAssignmentClass
{


    private $assignmentsWeights;
    private $user;
    private $rosterId;
    private FilterRosterAssignmentData $filterRosterAssignmentData;
    protected GradeBook $gradeBook;

    protected $mongoStudentAnswers;
    protected $studentPageStickers;
    protected $rosterAssignments;

    protected array $gradeBookRosterAssignmentArrayForCreate = [];

    public function __construct($rosterId,GradeBookData $gradeBookData,GradeBook $gradeBook)
    {
        $this->user = request()->user();
        $this->gradeBook = $gradeBook;
        $this->rosterId = $rosterId;
        $this->filterRosterAssignmentData = $gradeBookData->filter_roster_assignment_data;
        $this->assignmentsWeights = $gradeBookData->roster_assignments_weights;
        return $this;

    }


    public function prepare(){

        $rosterAssignmentClass = RosterAssignmentManagementFactory::create($this->user);
        $rosterAssignmentIds =$rosterAssignmentClass
            ->setFilter($this->filterRosterAssignmentData)
            ->myRosterAssignmentsIdsByRosterId($this->rosterId);

        $rosterAssignmentsIdsAsString = [];
        foreach ($rosterAssignmentIds as $rosterAssignmentsId) {
            $rosterAssignmentsIdsAsString[] = (string)$rosterAssignmentsId;
        }
        $this->mongoStudentAnswers = MongoStudentAnswer::whereIn('roster_assignment_id', $rosterAssignmentsIdsAsString)
            ->get();
//            ->cursor();


        $this->studentPageStickers = StudentPageSticker::whereIn('roster_assignment_id', $rosterAssignmentIds)
            ->with('Sticker')
            ->get();
//            ->cursor();

        $this->rosterAssignments = RosterAssignment::whereIn('id', $rosterAssignmentIds)
            ->get();
//            ->cursor();


        return [$rosterAssignmentIds,$this->rosterAssignments];

    }

    public function createGradeBookRosterAssignments(){
        $allWeight = 0;

        foreach ($this->rosterAssignments as $rosterAssignment) {
            $assignmentWeight = $this->assignmentsWeights[$rosterAssignment->id]??0;
            $allWeight += $assignmentWeight;

            $this->gradeBookRosterAssignmentArrayForCreate[] = [
                'grade_book_id' => $this->gradeBook->id,
                'roster_assignment_id' => $rosterAssignment->id,
                'weight' => $assignmentWeight,
                'created_at' => Carbon::now(),
            ];
        }
        GradeBookRosterAssignment::insert($this->gradeBookRosterAssignmentArrayForCreate);
        return $allWeight;

    }


    public function proccess($student,&$finalGrade,&$rosterAssignmentsCount){
        $student->roster_assignments_marks = [];
        $rosterAssignmentsMarks = [];
        foreach ($this->rosterAssignments as $rosterAssignment) {
            $rosterAssignmentsCount++;
            $assignmentWeight = $this->assignmentsWeights[$rosterAssignment->id]??0;
            $rosterAssignmentsMarks[$rosterAssignment->id] = [
                'final_mark' => 0,
                'roster_assignment_mark' => 0,
            ];

//            $this->gradeBookRosterAssignmentArrayForCreate[] = [
//              'grade_book_id' => $this->gradeBook->id,
//              'roster_assignment_id' => $rosterAssignment->id,
//              'weight' => $assignmentWeight,
//              'created_at' => Carbon::now(),
//            ];


            $rosterAssignmentsOriginalMark = $this->calculateRosterAssignmentFullMark(
                $rosterAssignment
            );

            $studentStickersMarks = $this->calculateStudentStickersMarks(
                $student,$rosterAssignment
            );

            $studentAnswersMarks = $this->calculateStudentAnswersMarks(
                $student,$rosterAssignment
            );
            $studentRosterAssignmentfinalMark = $studentStickersMarks + $studentAnswersMarks;

            if($rosterAssignmentsOriginalMark == 0)
                $studentRosterAssignmentPercentageMark = 0;
            else
                $studentRosterAssignmentPercentageMark = (
                        $studentRosterAssignmentfinalMark/$rosterAssignmentsOriginalMark
                    )*100;

            $rosterAssignmentsMarks[$rosterAssignment->id]['final_mark'] = round(
                ($studentRosterAssignmentPercentageMark*$assignmentWeight)/100,
                2
            );
            $rosterAssignmentsMarks[$rosterAssignment->id]['roster_assignment_mark'] = $rosterAssignmentsOriginalMark;

            $finalGrade+= $rosterAssignmentsMarks[$rosterAssignment->id]['final_mark'];
        }
        $student->roster_assignments_marks = $rosterAssignmentsMarks;

    }

    /**
     * @param RosterAssignment $rosterAssignment
     * @return float|int
     */
    private function calculateRosterAssignmentFullMark($rosterAssignment){

        return calculateRosterAssignmentFullMark($rosterAssignment);
    }

    /**
     * @param Student $student
     * @param RosterAssignment $rosterAssignment
     * @return int
     */
    private function calculateStudentStickersMarks($student,$rosterAssignment){
        $studentStickersMarks = 0;
        $studentPageStickersForDefinedStudent = $this->studentPageStickers
            ->where('student_id', $student->id)
            ->where('roster_assignment_id', $rosterAssignment->id)
            ->all();
        foreach ($studentPageStickersForDefinedStudent as $studentPageSticker) {
            $studentStickersMarks += $studentPageSticker->Sticker->mark;
        }
        return  $studentStickersMarks;
    }

    /**
     * @param Student $student
     * @param RosterAssignment $rosterAssignment
     * @return int
     */
    private function calculateStudentAnswersMarks($student,$rosterAssignment){
        $studentAnswersMarks = 0;
        $definedStudentAnswers = $this->mongoStudentAnswers
            ->where('roster_assignment_id', (string)$rosterAssignment->id)
            ->where('student_id', (string)$student->id)
            ->all();
//        if (empty($definedStudentAnswers)) {
//            continue;
//        }

        foreach ($definedStudentAnswers as $answer) {
            $questionMark = MarkServices::{$answer['question_type']}($answer['answer_body']);
            $studentAnswersMarks += $questionMark;
        }
        return  $studentAnswersMarks;
    }



    public function getArrayForCreate(){
        return $this->gradeBookRosterAssignmentArrayForCreate;
    }


}
