<?php

namespace Modules\Quiz\Traits\ResourceSharedData;


use App\Modules\User\Http\Resources\EducatorResource;
use App\Modules\User\Http\Resources\SchoolResource;
use App\Modules\User\Http\Resources\TeacherResource;
use Modules\Level\Http\Resources\LessonResource;
use Modules\Level\Http\Resources\LevelSubjectResource;
use Modules\Level\Http\Resources\UnitResource;
use Modules\Quiz\Http\Resources\QuizLessonResource;
use Modules\Quiz\Http\Resources\QuizUnitResource;
use Modules\Roster\Http\Resources\RosterResource;

trait QuizResourceSharedDataTrait
{

    public function getSharedData(){
        return [
            'id' => $this->id,
            'school_id' => isset($this->school_id)?(int)$this->school_id:$this->school_id,
            'teacher_id' => isset($this->teacher_id)?(int)$this->teacher_id:$this->teacher_id,
            'educator_id' => isset($this->educator_id)?(int)$this->educator_id:$this->educator_id,
            'roster_id' => isset($this->roster_id)?(int)$this->roster_id:$this->roster_id,
            'level_subject_id' => isset($this->level_subject_id)?(int)$this->level_subject_id:$this->level_subject_id,
//            'unit_id' => isset($this->unit_id)?(int)$this->unit_id:$this->unit_id,
//            'lesson_id' => isset($this->lesson_id)?(int)$this->lesson_id:$this->lesson_id,


            'name' => $this->name,
            'mark' => (float)$this->mark,
            'questions_count' => (int)$this->questions_count,
            'time' => (int)$this->time,
            'start_date' => refactorCreatedAtFormat($this->start_date),
            'end_date' => refactorCreatedAtFormat($this->end_date),

            'deleted' => (bool)$this->deleted,
            'deleted_at' => $this->deleted_at,

            'school' => new SchoolResource($this->whenLoaded('School')),

            'educator' => new EducatorResource($this->whenLoaded('Educator')),

            'teacher' => new TeacherResource($this->whenLoaded('Teacher')),

            'roster' => new RosterResource($this->whenLoaded('Roster')),


            'level_subject' => new LevelSubjectResource($this->whenLoaded('LevelSubject')),

//            'unit' => new UnitResource($this->whenLoaded('Unit')),
//
//            'lesson' => new LessonResource($this->whenLoaded('Lesson')),

            'quiz_units' => QuizUnitResource::collection($this->whenLoaded('QuizUnits')),

            'quiz_lessons' => QuizLessonResource::collection($this->whenLoaded('QuizLessons')),


        ];
    }

}
