<?php


namespace Modules\LearningResource\Http\Controllers\Classes\ManageLearningResourceByAccountType\MyOwnLearningResource;


use Illuminate\Database\Eloquent\Builder;
use Illuminate\Pagination\LengthAwarePaginator;
use Modules\LearningResource\Models\LearningResource;

abstract class BaseManageMyOwnLearningResourceByAccountTypeAbstract
{


    /**
     * @return Builder of LearningResource items
     */
    abstract public function getMyLearningResourceQuery();

    /**
     * @param mixed $id
     * @return Builder of LearningResource items
     */
    public function getMyLearningResourceByIdQuery($id){
        $learningResourceQuery = $this->getMyLearningResourceQuery();
        $learningResource = $learningResourceQuery->where('id',$id);
        return $learningResource;
    }

    /**
     * @return LengthAwarePaginator of LearningResource items
     */
    public function getMyLearningResourcePaginate(){
        $learningResourceQuery = $this->getMyLearningResourceQuery();
        $learningResourceQuery->with(['LevelSubject'=>function($query){
            return $query->with(['Level','Subject']);
        },'Unit','Lesson']);
        $questionsBank = $learningResourceQuery->paginate(10);
        return $questionsBank;
    }

    /**
     * @return LengthAwarePaginator of LearningResource items
     */
    public function getMyLearningResourceByTopicIdPaginate($topicId){
        $learningResourceQuery = $this->getMyLearningResourceQuery()
            ->where('topic_id',$topicId);
        $learningResourceQuery->with(['LevelSubject'=>function($query){
            return $query->with(['Level','Subject']);
        },'Unit','Lesson','Topic']);
        $questionsBank = $learningResourceQuery->paginate(10);
        return $questionsBank;
    }

    /**
     * @return LearningResource|null
     */
    public function getMyLearningResourceById($id){
        $learningResourceQuery = $this->getMyLearningResourceByIdQuery($id);
        $learningResource = $learningResourceQuery->first();
        return $learningResource;
    }

    /**
     * @return LearningResource
     */
    public function getMyLearningResourceByIdOrFail($id){
        $learningResourceQuery = $this->getMyLearningResourceByIdQuery($id);
        $learningResource = $learningResourceQuery->firstOrFail();
        return $learningResource;
    }

}
