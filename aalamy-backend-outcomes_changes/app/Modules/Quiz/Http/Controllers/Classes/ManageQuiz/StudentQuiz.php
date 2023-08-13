<?php


namespace Modules\Quiz\Http\Controllers\Classes\ManageQuiz;


use App\Exceptions\ErrorUnAuthorizationException;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Modules\Quiz\Models\Quiz;
use Modules\Quiz\Models\QuizStudent;
use Modules\Roster\Http\Controllers\Classes\ManageRoster\StudentRosterClass;
use Modules\User\Models\Student;

class StudentQuiz extends BaseQuizAbstract implements DisplayQuizInterface,AnswerableQuizInterface
{
    private Student $student;

    public function __construct(Student $student)
    {
        $this->student = $student;

    }

    /**
     * @return Builder
     */
    public function getMyQuizzesWithoutConstrainsQuery(){


        $studentRposterClass = new StudentRosterClass($this->student);
        $myRosterIds = $studentRposterClass->myRosters()->pluck('id')->toArray();
        $myQuizzesQuery = Quiz::query()
            ->whereIn('roster_id',$myRosterIds);
//            ->where('start_date','<=',Carbon::now())
//            ->where('end_date','>=',Carbon::now());

        return $myQuizzesQuery;
    }

    /**
     * @return Builder
     */
    public function getMyQuizzesQuery()
    {

        $myQuizzesQuery = $this->getMyQuizzesWithoutConstrainsQuery();
//            ->where(function ($query){
//                return $query->whereHas('QuizStudents',function ($query){
//                    return $query->where('student_id',$this->student->id)
//                        ->whereNull('end_date');
//                })->orWhere('end_date','<',Carbon::now());
//            });

        return $myQuizzesQuery;
    }

    /**
     * get my quizzes I had started it and still running by id
     * @return Quiz|Builder
     */
    public function getMyRunningQuizzesById($id){
        $myQuiz = $this->getMyQuizzesWithoutConstrainsQuery()
            ->whereHas('QuizStudents',function ($query){
                return $query->where('student_id',$this->student->id)
                    ->whereNull('end_date');
            })
            ->where('end_date','>=',Carbon::now())
            ->where('id',$id)
            ->first();
        return $myQuiz;
    }

    /**
     * return quizzes haven't ended yet
     * , and I haven't answered it yet
     * , or I have answered but haven't finished it yet
     * @return Builder|Quiz
     */
    public function getComingQuizzesQuery(){
        $myComingQuizzesQuery = $this->getMyQuizzesWithoutConstrainsQuery()
            ->where('end_date','>=',Carbon::now())
            ->whereDoesntHave('QuizStudents',function ($query){
                return $query->where('student_id',$this->student->id)
                    ->whereNotNull('end_date');
            });
        return $myComingQuizzesQuery;
    }


    /**
     * @return Builder|Quiz|Collection
     */
    public function getComingQuizzesByRosterId($rosterId){
        $myQuizzes = $this->getComingQuizzesQuery()
            ->where('roster_id',$rosterId)
            ->get();
        return $myQuizzes;
    }

    /**
     * @return Builder|Quiz|Collection
     */
    public function getComingQuizzesAll(){

        $rosterManagmentClass = new StudentRosterClass($this->student);
        $myRosterIds = $rosterManagmentClass->myRosters()->pluck('id')->toArray();


        $myQuizzes = $this->getComingQuizzesQuery()
            ->whereIn('roster_id',$myRosterIds)
            ->with([
                'LevelSubject.Subject',
                'LevelSubject.Level',
//                'Unit',
//                'Lesson',
                'QuizUnits.Unit',
                'QuizLessons.Lesson',
                'Educator.User',
                'School',
                'Teacher.User',
            ])
            ->get();
        return $myQuizzes;
    }


    /**
     * return quizzes haven't ended yet
     * , and have started
     * , and I haven't answered it yet
     * , or I have answered but haven't finished yet
     * @return Builder|Quiz|Collection
     */
    public function getQuizByIdICanAnswerItNow($id){
        $myQuizze = $this->getMyQuizzesWithoutConstrainsQuery()
            // ->where('start_date','<=',Carbon::now())
            // ->where('end_date','>=',Carbon::now())
            ->where('start_date','<=',Carbon::now())
            ->where('end_date','>=',Carbon::now())
            ->whereDoesntHave('QuizStudents',function ($query){
                return $query->where('student_id',$this->student->id)
                    ->whereNotNull('end_date');
            })
            ->find($id);
        return $myQuizze;
    }


    public function getMyEndedQuizzesByRosterIdAll($rosterId/*,?array $quizzesIds=null*/){
        return $this->getMyQuizzesQuery()
            ->where('roster_id',$rosterId)
            ->where(function ($query){
                return $query->where('end_date','<',Carbon::now())
                    ->orWhereHas('QuizStudents',function ($query){
                        return $query->where('student_id',$this->student->id)
                            ->where('end_date','<',Carbon::now());
                    });
            })
            /*->when(isset($quizzesIds),function ($query)use ($quizzesIds){
                return $query->whereIn('id',$quizzesIds);
            })*/
            ->get();
    }


    /**
     * return quiz answer for defined student(me $this->student)
     * check if the student has finished his quiz throw error
     * or if the quiz end up throw error too
     * @param $quizId
     * @param $studentId
     * @throws ErrorUnAuthorizationException
     */
    public function canShowQuizAnswersForStudent($quizId,$studentId){
        $quizStudent = QuizStudent::where('student_id',$studentId)
            ->where('quiz_id',$quizId)
            ->first();
    
    	if(is_null($quizStudent))//the student didn't answer on this quiz before
            return true;

        $quiz = Quiz::where('id',$quizStudent->quiz_id)
//            ->where('end_date','<',Carbon::now())
            ->firstOrFail();

        if($quiz->prevent_display_answers)//the quiz has finished
            throw new ErrorUnAuthorizationException();


        if(is_null($quizStudent))//the student didn't answer on this quiz before
            return true;

        if(!is_null($quizStudent->end_date))//the student has finished this quiz before
            return true;

//        if(is_null($quizStudent->end_date))//the student hasn't finished this quiz before
//            throw new ErrorUnAuthorizationException();


        if($quiz->end_date < Carbon::now())//the quiz has finished
            return true;

        throw new ErrorUnAuthorizationException();
//        ->where(function ($query){
//                return $query->whereNotNull('end_date')
//                    ->orWhereHas('Quiz',function ($query){
//                        return $query->where('end_date','<',Carbon::now());
//                    });
//            })
//            ->first();
//        if(is_null($quizStudent))
//            throw new ErrorUnAuthorizationException();
    }

}
