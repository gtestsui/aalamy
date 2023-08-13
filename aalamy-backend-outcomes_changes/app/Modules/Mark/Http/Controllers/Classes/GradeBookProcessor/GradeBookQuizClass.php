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
use Modules\Mark\Models\GradeBookQuiz;
use Modules\Mark\Models\MongoSession;
use Modules\Mark\Models\MongoStudentAnswer;
use Maatwebsite\Excel\Facades\Excel;
use Modules\Quiz\Http\Controllers\Classes\ManageQuiz\QuizManagementFactory;
use Modules\Quiz\Http\DTO\FilterQuizData;
use Modules\Quiz\Models\Quiz;
use Modules\RosterAssignment\Http\Controllers\Classes\ManageRosterAssignment\RosterAssignmentManagementFactory;
use Modules\RosterAssignment\Http\DTO\FilterRosterAssignmentData;
use Modules\RosterAssignment\Models\RosterAssignment;
use Modules\Sticker\Models\StudentPageSticker;
use Modules\User\Models\Student;
use Modules\User\Models\User;


class GradeBookQuizClass
{


    private $quizzesWeights;
    private $user;
    private $rosterId;
    private FilterQuizData $filterQuizData;
    protected GradeBook $gradeBook;

    protected $studentQuizzesWithMark;
    protected $quizzes;

    protected array $gradeBookQuizArrayForCreate = [];

    public function __construct($rosterId,GradeBookData $gradeBookData,GradeBook $gradeBook)
    {
        $this->user = request()->user();
        $this->gradeBook = $gradeBook;
        $this->rosterId = $rosterId;
        $this->filterQuizData = $gradeBookData->filter_quiz_data;
        $this->quizzesWeights = $gradeBookData->quizzes_weights;
        return $this;

    }


    public function prepare(){

        $quizManagmentClass = QuizManagementFactory::create($this->user);
        $this->quizzes = $quizManagmentClass
            ->setFilter($this->filterQuizData)
            ->getMyEndedQuizzesByRosterIdAll($this->rosterId);

        $this->studentQuizzesWithMark = Quiz::withoutGlobalScopes()
            ->where('quizzes.deleted',false)
            ->where('roster_id',$this->rosterId)
//            ->whereDate('quizzes.end_date','<=',Carbon::now())
            ->where('quizzes.end_date','<=',Carbon::now())
            ->when(isset($this->filterQuizData)&&!is_null($this->filterQuizData->quizzes_ids),function ($query){
                return $query->whereIn('quizzes.id',$this->filterQuizData->quizzes_ids);
            })
            ->join('quiz_questions',function ($join){
                $join->on('quiz_questions.quiz_id','quizzes.id');
                $join->where('quiz_questions.deleted',false);
            })
            ->leftjoin('quiz_question_student_answers',function ($join){
                $join->on('quiz_question_student_answers.quiz_question_id','quiz_questions.id');
                $join->where('quiz_question_student_answers.deleted',false);
            })
            ->leftjoin('students',function ($join){
                $join->on('students.id','quiz_question_student_answers.student_id');
                $join->where('students.deleted',false);
            })
            ->leftjoin('users','students.user_id','users.id')
            ->distinct()
            ->select('quizzes.id as quiz_id',
                'students.id as student_id',
                'users.fname as fname',
                'users.lname as lname',
                DB::raw('Sum(quiz_question_student_answers.mark ) as final_mark')
            )
            ->groupBy('students.id','users.image','quizzes.id','users.fname','users.lname')
            ->get()
            ->lazy();

        return $this->quizzes;

    }

    public function createGradeBookQuizzes(){
        $allWeight = 0;

        foreach ($this->quizzes as $quiz){
            $quizWeight = $this->quizzesWeights[$quiz->id]??0;
            $allWeight += $quizWeight;

            $this->gradeBookQuizArrayForCreate[] =[
                'grade_book_id' => $this->gradeBook->id,
                'quiz_id' => $quiz->id,
                'weight' => $quizWeight,
                'created_at' => Carbon::now(),
            ];

        }
        GradeBookQuiz::insert($this->gradeBookQuizArrayForCreate);
        return $allWeight;

    }


    public function proccess($student,&$finalGrade,&$quizzesCount){
        $quizMarks = [];
        foreach ($this->quizzes as $quiz){
            $quizWeight = $this->quizzesWeights[$quiz->id]??0;
            $quizzesCount++;
            $quizMarks[$quiz->id] = [
                'final_mark' => 0
            ];

//            $this->gradeBookQuizArrayForCreate[] =[
//              'grade_book_id' => $this->gradeBook->id,
//              'quiz_id' => $quiz->id,
//              'weight' => $quizWeight,
//              'created_at' => Carbon::now(),
//            ];

            $studentQuize = $this->studentQuizzesWithMark->where('student_id',$student->id)
                ->where('quiz_id',$quiz->id)
                ->first();

            if(is_null($studentQuize))
                continue;

            $quizMarkPrecentage = ($studentQuize->final_mark/100)*100;
            $quizFinalMark = ($quizMarkPrecentage*$quizWeight)/100;
            $quizMarks[$quiz->id] = [
                'final_mark' => round($quizFinalMark,2)
            ];
            $finalGrade+= $quizMarks[$quiz->id]['final_mark'];

        }
        $student->quizzes_marks = $quizMarks;

    }


    public function getArrayForCreate(){
        return $this->gradeBookQuizArrayForCreate;
    }

}
