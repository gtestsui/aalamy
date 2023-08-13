<?php


namespace Modules\Quiz\Http\Controllers\Classes\ManageQuiz;


use Illuminate\Database\Eloquent\Builder;
use Modules\Quiz\Http\DTO\FilterQuizData;
use Modules\Quiz\Models\Quiz;

interface OwnerQuizInterface
{


    public function getAllMyQuizzes();

    public function setFilter(FilterQuizData $filterQuizData);


    public function getMyQuizById($id);
    public function getMyQuizzesByRosterIdAll($roster_id);

    /**
     * @param $roster_id
     * @param array|null $quizzesIds if you want defined quizzes,else will return all if uoy ignored this param
     * @return Quiz|Builder
     */
    public function getMyEndedQuizzesByRosterIdAll($roster_id/*,?array $quizzesIds=null*/);

    public function getMyQuizByIdOrFail($id);




}
