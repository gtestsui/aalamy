<?php


namespace Modules\QuestionBank\Http\Controllers\Classes\ManageQuestionLibraryByAccountType\MyOwnQuestionLibrary;


use Illuminate\Database\Eloquent\Builder;
use Illuminate\Pagination\LengthAwarePaginator;
use Modules\QuestionBank\Models\QuestionBank;

abstract class BaseManageMyOwnQuestionLibraryByAccountTypeAbstract
{


    /**
     * @note my shared question with library (I can make actions on them)
     * @return Builder of QuestionBank items
     */
    abstract public function getMyQuestionLibraryQuery();

    /**
     * @param mixed $id
     * @return Builder of QuestionBank items
     */
    public function getMyQuestionLibraryByIdQuery($id){
        $questionLibraryQuery = $this->getMyQuestionLibraryQuery();
        $questionLibrary = $questionLibraryQuery->where('id',$id);
        return $questionLibrary;
    }

    /**
     * @return LengthAwarePaginator of QuestionBank items
     */
    public function getMyQuestionLibraryPaginate(?array $filter=null){
        $questionLibraryQuery = $this->getMyQuestionLibraryQuery()
            ->filterMyQuestionLibrary($filter)
            ->with(['LevelSubject'=>function($query){
                return $query->with(['Level','Subject']);
            },'Unit','Lesson']);
        $questionsLibrary = $questionLibraryQuery->paginate(10);
        return $questionsLibrary;
    }

    /**
     * @return QuestionBank|null
     */
    public function getMyQuestionLibraryById($id){
        $questionLibraryQuery = $this->getMyQuestionLibraryByIdQuery($id);
        $questionLibrary = $questionLibraryQuery->first();
        return $questionLibrary;
    }

    /**
     * @return QuestionBank
     */
    public function getMyQuestionLibraryByIdOrFail($id){
        $questionLibraryQuery = $this->getMyQuestionLibraryByIdQuery($id);
        $questionLibrary = $questionLibraryQuery->firstOrFail();
        return $questionLibrary;
    }

}
