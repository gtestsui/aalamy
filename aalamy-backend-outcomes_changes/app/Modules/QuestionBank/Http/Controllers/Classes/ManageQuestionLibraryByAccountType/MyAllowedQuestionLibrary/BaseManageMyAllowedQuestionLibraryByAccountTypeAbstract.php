<?php


namespace Modules\QuestionBank\Http\Controllers\Classes\ManageQuestionLibraryByAccountType\MyAllowedQuestionLibrary;


use Illuminate\Database\Eloquent\Builder;
use Illuminate\Pagination\LengthAwarePaginator;
use Modules\QuestionBank\Models\QuestionBank;

abstract class BaseManageMyAllowedQuestionLibraryByAccountTypeAbstract
{


    /**
     * @note my shared and another question I have permission to see it
     * @return Builder of QuestionBank items
     */
    abstract public function getMyAllowedQuestionLibraryQuery();

    /**
     * @param mixed $id
     * @return Builder of QuestionBank items
     */
    public function getMyAllowedQuestionLibraryByIdQuery($id){
        $questionLibraryQuery = $this->getMyAllowedQuestionLibraryQuery();
        $questionLibrary = $questionLibraryQuery->where('id',$id);
        return $questionLibrary;
    }

    /**
     * @return LengthAwarePaginator of QuestionBank items
     */
    public function getMyAllowedQuestionLibraryPaginate(?array $filter=null){
        $questionLibraryQuery = $this->getMyAllowedQuestionLibraryQuery()
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
    public function getMyAllowedQuestionLibraryById($id){
        $questionLibraryQuery = $this->getMyAllowedQuestionLibraryByIdQuery($id);
        $questionLibrary = $questionLibraryQuery->first();
        return $questionLibrary;
    }

    /**
     * @return QuestionBank
     */
    public function getMyAllowedQuestionLibraryByIdOrFail($id){
        $questionLibraryQuery = $this->getMyAllowedQuestionLibraryByIdQuery($id);
        $questionLibrary = $questionLibraryQuery->firstOrFail();
        return $questionLibrary;
    }

}
