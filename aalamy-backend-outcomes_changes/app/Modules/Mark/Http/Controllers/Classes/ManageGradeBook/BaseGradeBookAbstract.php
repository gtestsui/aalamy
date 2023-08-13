<?php


namespace Modules\Mark\Http\Controllers\Classes\ManageGradeBook;


use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;
use Modules\Mark\Http\DTO\GradeBookData;
use Modules\Mark\Models\GradeBook;
use Modules\Quiz\Http\DTO\FilterQuizData;
use Modules\Quiz\Models\Quiz;

abstract class BaseGradeBookAbstract
{


    /**
     * @return Builder|GradeBook
     */
    abstract public function getMyGradeBooksQuery();

    /**
     * @return  GradeBook
     */
    abstract public function createGradeBook(GradeBookData $gradeBookData);



    /**
     * @return Builder|GradeBook|Collection
     */
    public function getAllMyGradeBooks(){
        return $this->getMyGradeBooksQuery()->get();
    }

    /**
     * @return LengthAwarePaginator
     */
    public function getMyGradeBooksPaginate(){
        return $this->getMyGradeBooksQuery()
            ->with([
                'Teacher',
                'Roster',
                'LevelSubject.Level',
                'LevelSubject.Subject',
//                'Quizzes',
//                'RosterAssignments',
//                'ExternalMarks',
            ])
            ->paginate(10);
    }


    public function getMyGradeBookById($id){
        return $this->getMyGradeBooksQuery()
            ->where('id',$id)
            ->first();
    }

//    public function getMyQuizByIdOrFail($id){
//        return $this->getMyQuizzesQuery()->where('id',$id)->firstOrFail();
//    }
//
//    public function getMyQuizzesByRosterIdPaginate($rosterId,$num=10){
//        return $this->getMyQuizzesQuery()->where('roster_id',$rosterId)->paginate($num);
//    }
//
//    public function getMyQuizzesByRosterIdAll($rosterId){
//        return $this->getMyQuizzesQuery()->where('roster_id',$rosterId)->get();
//    }
//
//    public function getMyEndedQuizzesByRosterIdAll($rosterId/*,?array $quizzesIds=null*/){
//        return $this->getMyQuizzesQuery()
//            ->where('roster_id',$rosterId)
//            ->whereDate('end_date','<=',Carbon::now())
//            ->when(isset($quizzesIds),function ($query)use ($quizzesIds){
//                return $query->whereIn('id',$quizzesIds);
//            })
//            ->get();
//    }



}
