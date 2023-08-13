<?php


namespace Modules\Quiz\Http\Controllers\Classes\ManageQuiz;


use App\Exceptions\ErrorUnAuthorizationException;
use Illuminate\Database\Eloquent\Builder;
use Modules\Quiz\Models\Quiz;
use Modules\Quiz\Models\QuizStudent;
use Modules\User\Models\Educator;

class EducatorQuiz extends BaseQuizAbstract implements OwnerQuizInterface,DisplayQuizInterface
{

    private Educator $educator;

    public function __construct(Educator $educator)
    {
        $this->educator = $educator;
    }

    /**
     * @return Builder
     */
    public function getMyQuizzesQuery(){
        $myQuizzesQuery = Quiz::query()
            ->where('educator_id',$this->educator->id)
            ->filter($this->filterQuizData);

        return $myQuizzesQuery;
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
