<?php


namespace Modules\Quiz\Http\Controllers\Classes\ManageQuiz;


use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Modules\Quiz\Http\DTO\FilterQuizData;
use Modules\Quiz\Models\Quiz;

abstract class BaseQuizAbstract
{


    protected ?FilterQuizData $filterQuizData=null;


    /**
     * @return Builder|Quiz
     */
    abstract protected function getMyQuizzesQuery();



    /**
     * @return static
     */
    public function setFilter(FilterQuizData $filterQuizData){
        $this->filterQuizData = $filterQuizData;
        return $this;
    }

    public function getAllMyQuizzes(){
        return $this->getMyQuizzesQuery()->get();
    }


    public function getMyQuizById($id){
        return $this->getMyQuizzesQuery()->where('id',$id)->first();
    }

    public function getMyQuizByIdOrFail($id){
        return $this->getMyQuizzesQuery()->where('id',$id)->firstOrFail();
    }

    public function getMyQuizzesByRosterIdPaginate($rosterId,$num=10){
        return $this->getMyQuizzesQuery()->where('roster_id',$rosterId)->paginate($num);
    }

    public function getMyQuizzesByRosterIdAll($rosterId){
        return $this->getMyQuizzesQuery()->where('roster_id',$rosterId)
            ->with(['LevelSubject.Level',
                'LevelSubject.Subject',
//                'Unit',
//                'Lesson',
                'QuizUnits.Unit',
                'QuizLessons.Lesson'
            ])
            ->get();
    }

    public function getMyEndedQuizzesByRosterIdAll($rosterId/*,?array $quizzesIds=null*/){
        return $this->getMyQuizzesQuery()
            ->where('roster_id',$rosterId)
            ->where('end_date','<=',Carbon::now())
           /* ->when(isset($quizzesIds),function ($query)use ($quizzesIds){
                return $query->whereIn('id',$quizzesIds);
            })*/
            ->get();
    }



}
