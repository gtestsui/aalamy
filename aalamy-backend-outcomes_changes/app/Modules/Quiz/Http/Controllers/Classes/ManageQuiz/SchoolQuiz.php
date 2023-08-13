<?php


namespace Modules\Quiz\Http\Controllers\Classes\ManageQuiz;


use App\Exceptions\ErrorUnAuthorizationException;
use Illuminate\Database\Eloquent\Builder;
use Modules\Quiz\Models\Quiz;
use Modules\Quiz\Models\QuizStudent;
use Modules\User\Models\School;

class SchoolQuiz extends BaseQuizAbstract  implements OwnerQuizInterface,DisplayQuizInterface
{
    private School $school;

    public function __construct(School $school)
    {
        $this->school = $school;

    }

    /**
     * @return Builder
     */
    public function getMyQuizzesQuery(){
        $myStickersQuery = Quiz::query()
            ->where('school_id',$this->school->id)
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
