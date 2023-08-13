<?php


namespace Modules\LearningResource\Http\Controllers\Classes\ManageTopicByAccountType\MyAllwoedTopic;


use App\Exceptions\ErrorMsgException;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Pagination\LengthAwarePaginator;
use Modules\LearningResource\Http\Controllers\Classes\LearningResourceServices;
use Modules\LearningResource\Http\Controllers\Classes\ManageTopicByAccountType\TopicConstants;
use Modules\LearningResource\Models\Topic;

abstract class BaseManageMyAllowedTopicByAccountTypeAbstract
{



    /**
     * @note the read is main process for all concrete classes
     * @return Builder of Topic items
     */
    abstract public function getMyAllowedTopicReadQuery();
    abstract public function checkCanAddInsideTopic(Topic $topic);


    public function changeAccessType($accessType){
        LearningResourceServices::checkValidTopicAccessType($accessType);
        $this->accessType = $accessType;
        return $this;
    }

    public function getMyAllowedTopicQuery(){
        if($this->accessType == TopicConstants::READ_ACCESS_TYPE){
            return $this->getMyAllowedTopicReadQuery();
        }elseif ($this->accessType == TopicConstants::WRITE_ACCESS_TYPE){
            return $this->getMyAllowedTopicWriteQuery();
        }
        throw new ErrorMsgException('invalid access type');
    }

    /**
     * @param mixed $id
     * @return Builder of Topic items
     */
    public function getMyAllowedTopicByIdQuery($id){
        $topicQuery = $this->getMyAllowedTopicQuery();
        $topic = $topicQuery->where('id',$id);
        return $topic;
    }

    /**
     * @return LengthAwarePaginator of Topic items
     */
    public function getMyAllowedTopicPaginate(){
        $topicQuery = $this->getMyAllowedTopicQuery();
        $questionsBank = $topicQuery->paginate(10);
        return $questionsBank;
    }

    /**
     * @return LengthAwarePaginator of Topic items
     */
    public function getMyAllowedRootTopicPaginate(){
        $questionsBank = $this->getMyAllowedTopicQuery()
            ->isRoot()
            ->paginate(25);
        return $questionsBank;
    }

    /**
     * @return LengthAwarePaginator of Topic items
     */
    public function getMyAllowedTopicByTopicIdPaginate($topicId){
        $questionsBank = $this->getMyAllowedTopicQuery()
            ->where('topic_id',$topicId)
            ->with('Parent')
            ->paginate(10);
        return $questionsBank;
    }

    /**
     * @return Topic|null
     */
    public function getMyAllowedTopicById($id){
        $topicQuery = $this->getMyAllowedTopicByIdQuery($id);
        $topic = $topicQuery->first();
        return $topic;
    }

    /**
     * @return Topic
     */
    public function getMyAllowedTopicByIdOrFail($id){
        $topicQuery = $this->getMyAllowedTopicByIdQuery($id);
        $topic = $topicQuery->firstOrFail();
        return $topic;
    }

}
