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


class GradeBookExternalMarkClass
{


    protected GradeBook $gradeBook;

    private $externalMarksWeight;
    private array $externalMarks;

    protected $mapOfExternalMarks;

    public bool $thereAnExternalMarks=false;

    protected array $gradeBookExternalMarksarrayForCreate = [];

    public function __construct(GradeBookData $gradeBookData,GradeBook $gradeBook)
    {
        $this->gradeBook = $gradeBook;

        $this->externalMarksWeight = $gradeBookData->external_marks_weight;
        $this->externalMarks = $gradeBookData->external_marks;
        return $this;

    }



    public function prepare(){


        foreach ($this->externalMarks as $externalMark){
            //to display the external marks column
            if(!$this->thereAnExternalMarks && $externalMark['mark']>0)
                $this->thereAnExternalMarks = true;

            $this->mapOfExternalMarks[$externalMark['student_id']] = $externalMark['mark'];

        }


    }



    public function proccess($student,&$finalGrade){
        $studentExternalMark = isset($this->mapOfExternalMarks[$student->id])
            ?round(
                ($this->mapOfExternalMarks[$student->id]*$this->externalMarksWeight)/100,2
            )
            :0;


        $this->gradeBookExternalMarksarrayForCreate [] =[
          'grade_book_id' => $this->gradeBook->id,
          'student_id' => $student->id,
          'mark' => $studentExternalMark,
          'created_at' => Carbon::now(),

        ];
        $finalGrade+= $studentExternalMark;
        $student->external_mark = $studentExternalMark;

    }

    public function getArrayForCreate(){
        return $this->gradeBookExternalMarksarrayForCreate;
    }




}
