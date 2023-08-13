<?php


namespace Modules\Quiz\Http\Controllers\Classes\ManageQuiz;


use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Modules\Quiz\Models\Quiz;

interface AnswerableQuizInterface
{


    /**
     * get my quizzes i had started it and still running by id
     * @return Quiz|Builder
     */
    public function getMyRunningQuizzesById($id);
    /**
     * @return Builder|Quiz|Collection
     */
    public function getComingQuizzesByRosterId($roster_id);
    /**
     * @return Builder|Quiz|Collection
     */
    public function getQuizByIdICanAnswerItNow($id);




}
