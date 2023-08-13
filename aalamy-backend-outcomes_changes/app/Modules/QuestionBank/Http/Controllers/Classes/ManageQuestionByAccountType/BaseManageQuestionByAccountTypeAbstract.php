<?php


namespace Modules\QuestionBank\Http\Controllers\Classes\ManageQuestionByAccountType;


use App\Exceptions\ErrorMsgException;
use App\Http\Controllers\Classes\ApplicationModules;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Pagination\LengthAwarePaginator;
use Modules\QuestionBank\Models\QuestionBank;

abstract class BaseManageQuestionByAccountTypeAbstract
{


    protected $softDeleted=null;

    /**
     * @return Builder|QuestionBank of QuestionBank items
     */
    abstract public function getMyQuestionBankQuery();

    /**
     * fo
     */
    public function setTrashed($softDeleted){
        $this->softDeleted = $softDeleted;
        return $this;
    }

    /**
     * @param mixed $id
     * @return Builder of QuestionBank items
     */
    public function getMyQuestionBankByIdQuery($id){
        $questionBankQuery = $this->getMyQuestionBankQuery();
        $questionBank = $questionBankQuery->where('id',$id);
        return $questionBank;
    }

    /**
     * @return LengthAwarePaginator of QuestionBank items
     */
    public function getMyQuestionBankPaginate(?array $filter=null){
        $questionsBank = $this->getMyQuestionBankQuery()
            ->filterMyQuestionBank($filter)
            ->with([
                'Unit',
                'Lesson',
                'LevelSubject.Level',
                'LevelSubject.Subject',
            ])
            ->when(isset($filter['with_question_body']) && isset($filter['question_type']),function ($query)use ($filter){
                return $query->withDefinedQuestionType($filter['question_type']);
            })
            ->paginate(10);
        return $questionsBank;
    }

    /**
     * @return LengthAwarePaginator of QuestionBank items
     */
    public function getMyQuestionBankPaginateForAdmin(?array $filter=null){
        $questionsBank = $this->getMyQuestionBankQuery()
            ->filterMyQuestionBank($filter)
            ->with([
                'UnitEvenItsDeleted',
                'LessonEvenItsDeleted',
                'LevelSubjectEvenItsDeleted.LevelEvenItsDeleted',
                'LevelSubjectEvenItsDeleted.SubjectEvenItsDeleted',
            ])
            ->when(isset($filter['with_question_body']) && isset($filter['question_type']),function ($query)use ($filter){
                return $query->withDefinedQuestionType($filter['question_type']);
            })
            ->search(request()->key,[],[
                'UnitEvenItsDeleted',
                'LessonEvenItsDeleted',
                'LevelSubjectEvenItsDeleted'=>[
                    'LevelEvenItsDeleted',
                    'SubjectEvenItsDeleted'
                ],
            ])
            ->trashed($this->softDeleted)
            ->paginate(10);
        return $questionsBank;
    }


    /**
     * @return QuestionBank|null|Builder
     */
    public function getMyQuestionBankById($id){
        $questionBankQuery = $this->getMyQuestionBankByIdQuery($id);
        $questionBank = $questionBankQuery->first();
        return $questionBank;
    }

    /**
     * @return QuestionBank|Builder
     */
    public function getMyQuestionBankByIdOrFail($id){
        $questionBankQuery = $this->getMyQuestionBankByIdQuery($id);
        $questionBank = $questionBankQuery->firstOrFail();
        return $questionBank;
    }

}
