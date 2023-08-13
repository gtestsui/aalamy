<?php

namespace Modules\LearningResource\Http\Resources;

use App\Http\Traits\PaginationResources;
use App\Modules\User\Http\Resources\EducatorResource;
use App\Modules\User\Http\Resources\SchoolResource;
use App\Modules\User\Http\Resources\TeacherResource;
use Illuminate\Http\Resources\Json\JsonResource;
use Modules\LearningResource\Http\Controllers\Classes\LearningResourceServices;
use Modules\User\Traits\CatchUserTypeAndObject;
use Modules\Level\Http\Resources\LessonResource;
use Modules\Level\Http\Resources\LevelSubjectResource;
use Modules\Level\Http\Resources\UnitResource;

class LearningResourceResource extends JsonResource
{
    use PaginationResources,CatchUserTypeAndObject;


    public function checkCanUpdate(){
        if(is_null(Self::$userType) || is_null(Self::$userObject) )
            return false;

        if(LearningResourceServices::itsMyLearningResource($this,Self::$userObject))
            return true;

        return false;
    }

    public function checkCanDelete(){
        if(is_null(Self::$userType) || is_null(Self::$userObject) )
            return false;

        if(LearningResourceServices::itsMyLearningResource($this,Self::$userObject)
            || $this->imSchoolAndLearningResourceBelongsToMe())
            return true;
        return false;
    }


    private function imSchoolAndLearningResourceBelongsToMe(){
        if(Self::$userType=='school'
            && LearningResourceServices::learningResourceBelongsToSchool($this,Self::$userObject->id)
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
            'share_type' => $this->share_type,

            'user_id' => isset($this->user_id)?(int)$this->user_id:$this->user_id,
            'topic_id' => isset($this->topic_id)?(int)$this->topic_id:$this->topic_id,
            'school_id' => isset($this->school_id)?(int)$this->school_id:$this->school_id,
            'teacher_id' => isset($this->teacher_id)?(int)$this->teacher_id:$this->teacher_id,
            'educator_id' => isset($this->educator_id)?(int)$this->educator_id:$this->educator_id,

            'assignment_id' => isset($this->assignment_id)?(int)$this->assignment_id:$this->assignment_id,
            'level_subject_id' => isset($this->level_subject_id)?(int)$this->level_subject_id:$this->level_subject_id,
            'unit_id' => isset($this->unit_id)?(int)$this->unit_id:$this->unit_id,
            'lesson_id' => isset($this->lesson_id)?(int)$this->lesson_id:$this->lesson_id,

            'name' => $this->name,
            'file' => $this->file,
            'file_type' => $this->file_type,

            'deleted' => (bool)$this->deleted,
            'deleted_at' => $this->deleted_at,

            'topic' => new TopicResource($this->whenLoaded('Topic')),

            'school' => new SchoolResource($this->whenLoaded('School')),
            'teacher' => new TeacherResource($this->whenLoaded('Teacher')),
            'educator' => new EducatorResource($this->whenLoaded('Educator')),

            'level_subject' => new LevelSubjectResource($this->whenLoaded('LevelSubject')),
            'unit' => new UnitResource($this->whenLoaded('Unit')),
            'lesson' => new LessonResource($this->whenLoaded('Lesson')),
        ];
    }

}
