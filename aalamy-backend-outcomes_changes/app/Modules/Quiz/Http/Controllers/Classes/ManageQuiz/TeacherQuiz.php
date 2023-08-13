<?php


namespace Modules\Quiz\Http\Controllers\Classes\ManageQuiz;


use App\Exceptions\ErrorUnAuthorizationException;
use Illuminate\Database\Eloquent\Builder;
use Modules\Quiz\Models\Quiz;
use Modules\Quiz\Models\QuizStudent;
use Modules\User\Models\Teacher;

class TeacherQuiz extends BaseQuizAbstract implements OwnerQuizInterface,DisplayQuizInterface
{
    private Teacher $teacher;

    public function __construct(Teacher $teacher)
    {
        $this->teacher = $teacher;

    }

    /**
     * @return Builder
     */
    public function getMyQuizzesQuery(){
        $myStickersQuery = Quiz::query()
            ->where('teacher_id',$this->teacher->id)
            ->filter($this->filterQuizData);

        return $myStickersQuery;
    }

    public function canShowQuizAnswersForStudent($quizId,$studentId){
        $quiz = $this->getMyQuizById($quizId);
        if(is_null($quiz))
            throw new ErrorUnAuthorizationException();

        $quizStudent = QuizStudent::where('student_id',$studentId)
            ->where('quiz_id',$quizId)
            ->first();
        if(is_null($quizStudent))
            throw new ErrorUnAuthorizationException();
    }

}
