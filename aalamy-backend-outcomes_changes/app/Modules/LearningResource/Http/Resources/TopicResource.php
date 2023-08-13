<?php

namespace Modules\LearningResource\Http\Resources;

use App\Exceptions\ErrorMsgException;
use App\Http\Controllers\Classes\ApplicationModules;
use App\Http\Traits\PaginationResources;
use App\Modules\User\Http\Resources\EducatorResource;
use App\Modules\User\Http\Resources\SchoolResource;
use App\Modules\User\Http\Resources\TeacherResource;
use Illuminate\Http\Resources\Json\JsonResource;
use Modules\LearningResource\Http\Controllers\Classes\LearningResourceServices;
use Modules\User\Http\Resources\UserResource;
use Modules\User\Traits\CatchUserTypeAndObject;

class TopicResource extends JsonResource
{
    use PaginationResources,CatchUserTypeAndObject;


    public function checkCanUpdate(){
        if(is_null(Self::$userType) || is_null(Self::$userObject) )
            return false;

        if(LearningResourceServices::itsMyTopic($this,Self::$userObject))
            return true;

        return false;
    }

    public function checkCanDelete(){
        if(is_null(Self::$userType) || is_null(Self::$userObject) )
            return false;
        if(LearningResourceServices::itsMyTopic($this,Self::$userObject)
            || $this->imSchoolAndTopicBelongsToMe())
            return true;
        return false;
    }


    private function imSchoolAndTopicBelongsToMe(){
        if(Self::$userType=='school'
            && LearningResourceServices::topicBelongsToSchool($this,Self::$userObject->id)
        )
            return true;
        return  false;
    }




    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'update_authorization' => $this->checkCanUpdate(),
            'delete_authorization' => $this->checkCanDelete(),
            'read_share_type' => $this->read_share_type,
            'write_share_type' => $this->write_share_type,
            'user_id' => isset($this->user_id)?(int)$this->user_id:$this->user_id,
            'topic_id' => isset($this->topic_id)?(int)$this->topic_id:$this->topic_id,
            'school_id' => isset($this->school_id)?(int)$this->school_id:$this->school_id,
            'teacher_id' => isset($this->teacher_id)?(int)$this->teacher_id:$this->teacher_id,
            'educator_id' => isset($this->educator_id)?(int)$this->educator_id:$this->educator_id,
            'name' => $this->name,

            'deleted' => (bool)$this->deleted,
            'deleted_at' => $this->deleted_at,

            'parent' => new TopicResource($this->whenLoaded('Parent')),
            'teacher' => new TeacherResource($this->whenLoaded('Teacher')),
            'school' => new SchoolResource($this->whenLoaded('School')),
            'educator' => new EducatorResource($this->whenLoaded('Educator')),
            'user' => new UserResource($this->whenLoaded('User')),
        ];
    }



}
