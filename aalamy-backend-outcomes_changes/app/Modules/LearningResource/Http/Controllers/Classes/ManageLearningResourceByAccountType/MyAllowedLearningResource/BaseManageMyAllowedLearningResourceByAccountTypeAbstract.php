<?php


namespace Modules\LearningResource\Http\Controllers\Classes\ManageLearningResourceByAccountType\MyAllowedLearningResource;


use Illuminate\Database\Eloquent\Builder;
use Illuminate\Pagination\LengthAwarePaginator;
use Modules\LearningResource\Models\LearningResource;

abstract class BaseManageMyAllowedLearningResourceByAccountTypeAbstract
{


    /**
     * @return Builder of LearningResource items
     */
    abstract public function getMyAllowedLearningResourceQuery();

    /**
     * @param mixed $id
     * @return Builder of LearningResource items
     */
    public function getMyAllowedLearningResourceByIdQuery($id){
        $learningResourceQuery = $this->getMyAllowedLearningResourceQuery();
        $learningResource = $learningResourceQuery->where('id',$id);
        return $learningResource;
    }

    /**
     * @return LengthAwarePaginator of LearningResource items
     */
    public function getMyAllowedLearningResourcePaginate(){
        $learningResourceQuery = $this->getMyAllowedLearningResourceQuery();
        $learningResourceQuery->with(['LevelSubject'=>function($query){
            return $query->with(['Level','Subject']);
        },'Unit','Lesson']);
        $questionsBank = $learningResourceQuery->paginate(10);
        return $questionsBank;
    }

    /**
     * @return LengthAwarePaginator of LearningResource items
     */
    public function getMyAllowedLearningResourceByTopicIdPaginate($topicId){
        $learningResourceQuery = $this->getMyAllowedLearningResourceQuery()
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
    public function getMyAllowedLearningResourceById($id){
        $learningResourceQuery = $this->getMyAllowedLearningResourceByIdQuery($id);
        $learningResource = $learningResourceQuery->first();
        return $learningResource;
    }

    /**
     * @return LearningResource
     */
    public function getMyAllowedLearningResourceByIdOrFail($id){
        $learningResourceQuery = $this->getMyAllowedLearningResourceByIdQuery($id);
        $learningResource = $learningResourceQuery->firstOrFail();
        return $learningResource;
    }

}
