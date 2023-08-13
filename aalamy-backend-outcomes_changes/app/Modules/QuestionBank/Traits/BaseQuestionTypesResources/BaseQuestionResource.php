<?php

namespace Modules\QuestionBank\Traits\BaseQuestionTypesResources;


use App\Modules\User\Http\Resources\EducatorResource;
use App\Modules\User\Http\Resources\SchoolResource;
use App\Modules\User\Http\Resources\TeacherResource;
use Modules\Level\Http\Resources\LessonResource;
use Modules\Level\Http\Resources\LevelSubjectResource;
use Modules\Level\Http\Resources\UnitResource;

trait BaseQuestionResource
{

    /**
     * @return array
     *
     */
    public function baseResource(){
        return [
            'id' => $this->id,
            'question' => $this->question,
            'question_type' => $this->question_type,

            'difficult_level' => (int)$this->difficult_level,
            'school_id' => isset($this->school_id)?(int)$this->school_id:$this->school_id,
            'teacher_id' => isset($this->teacher_id)?(int)$this->teacher_id:$this->teacher_id,
            'educator_id' => isset($this->educator_id)?(int)$this->educator_id:$this->educator_id,

            'level_subject_id' => isset($this->level_subject_id)?(int)$this->level_subject_id:$this->level_subject_id,
            'unit_id' => isset($this->unit_id)?(int)$this->unit_id:$this->unit_id,
            'lesson_id' => isset($this->lesson_id)?(int)$this->lesson_id:$this->lesson_id,

            'deleted' => (bool)$this->deleted,
            'deleted_at' => $this->deleted_at,

            'school' => $this->relationLoaded('School')
                ?new SchoolResource($this->School)
                :new SchoolResource($this->whenLoaded('SchoolEvenItsDeleted')),

            'teacher' => $this->relationLoaded('Teacher')
                ?new TeacherResource($this->Teacher)
                :new TeacherResource($this->whenLoaded('TeacherEvenItsDeleted')),

            'educator' => $this->relationLoaded('Educator')
                ?new EducatorResource($this->Educator)
                :new EducatorResource($this->whenLoaded('EducatorEvenItsDeleted')),

            'level_subject' => $this->relationLoaded('LevelSubject')
                ?new LevelSubjectResource($this->LevelSubject)
                :new LevelSubjectResource($this->whenLoaded('LevelSubjectEvenItsDeleted')),

            'unit' => $this->relationLoaded('Unit')
                ?new UnitResource($this->Unit)
                :new UnitResource($this->whenLoaded('UnitEvenItsDeleted')),

            'lesson' => $this->relationLoaded('Lesson')
                ?new LessonResource($this->Lesson)
                :new LessonResource($this->whenLoaded('LessonEvenItsDeleted')),

        ];
    }

}
