<?php


namespace Modules\LearningResource\Http\Controllers\Classes\ManageTopicByAccountType\MyOwnTopic;


use Illuminate\Database\Eloquent\Builder;
use Illuminate\Pagination\LengthAwarePaginator;
use Modules\LearningResource\Models\Topic;

abstract class BaseManageMyOwnTopicByAccountTypeAbstract
{


    /**
     * @return Builder of Topic items
     */
    abstract public function getMyOwnTopicQuery();

//    /**
//     * @return Builder of Topic items
//     */
//    abstract public function getMyOwnTopicJustForDisplayQuery();

    /**
     * @param mixed $id
     * @return Builder of Topic items
     */
    public function getMyTopicByIdQuery($id){
        $topicQuery = $this->getMyOwnTopicQuery();
        $topic = $topicQuery->where('id',$id);
        return $topic;
    }

    /**
     * @return LengthAwarePaginator of Topic items
     */
    public function getMyTopicPaginate(){
        $topicQuery = $this->getMyOwnTopicQuery();
        $questionsBank = $topicQuery->paginate(10);
        return $questionsBank;
    }

    /**
     * @return LengthAwarePaginator of Topic items
     */
    public function getMyRootTopicPaginate(){
//        $questionsBank = $this->getMyOwnTopicJustForDisplayQuery()
        $questionsBank = $this->getMyOwnTopicQuery()
            ->isRoot()
            ->paginate(25);
        return $questionsBank;
    }

    /**
     * @return LengthAwarePaginator of Topic items
     */
    public function getMyTopicByTopicIdPaginate($topicId){
        $questionsBank = $this->getMyOwnTopicQuery()
            ->where('topic_id',$topicId)
            ->with('Parent')
            ->paginate(10);
        return $questionsBank;
    }

    /**
     * @return Topic|null
     */
    public function getMyTopicById($id){
        $topicQuery = $this->getMyTopicByIdQuery($id);
        $topic = $topicQuery->first();
        return $topic;
    }

    /**
     * @return Topic
     */
    public function getMyTopicByIdOrFail($id){
        $topicQuery = $this->getMyTopicByIdQuery($id);
        $topic = $topicQuery->firstOrFail();
        return $topic;
    }

}
