<?php

namespace Modules\Mark\Http\Resources;

use App\Http\Traits\PaginationResources;
use App\Modules\User\Http\Resources\EducatorResource;
use App\Modules\User\Http\Resources\SchoolResource;
use App\Modules\User\Http\Resources\StudentResource;
use App\Modules\User\Http\Resources\TeacherResource;
use Illuminate\Http\Resources\Json\JsonResource;
use Modules\Assignment\Http\Resources\AssignmentResource;
use Modules\Level\Http\Resources\LevelSubjectResource;
use Modules\Roster\Http\Resources\RosterResource;

class GradeBookResource extends JsonResource
{
    use PaginationResources;


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
            'grade_book_name' => $this->grade_book_name,
            'school_id' => isset($this->school_id)?(int)$this->school_id:null,
            'teacher_id' => isset($this->teacher_id)?(int)$this->teacher_id:null,
            'educator_id' => isset($this->educator_id)?(int)$this->educator_id:null,
            'roster_id' => isset($this->roster_id)?(int)$this->roster_id:null,
            'level_subject_id' => isset($this->level_subject_id)?(int)$this->level_subject_id:null,
            'external_marks_weight' => isset($this->external_marks_weight)?(int)$this->external_marks_weight:null,
            'file' => $this->file,


            'school' => new SchoolResource($this->whenLoaded('School')),
            'teacher' => new TeacherResource($this->whenLoaded('Teacher')),
            'educator' => new EducatorResource($this->whenLoaded('Educator')),
            'roster' => new RosterResource($this->whenLoaded('Roster')),
            'level_subject' => new LevelSubjectResource($this->whenLoaded('LevelSubject')),

            'grade_book_roster_assignments' => GradeBookRosterAssignmentResource::collection($this->whenLoaded('GradeBookRosterAssignments')),
            'grade_book_quizzes' => GradeBookQuizResource::collection($this->whenLoaded('GradeBookQuizzes')),
            'grade_book_external_marks' => GradeBookExternalMarkResource::collection($this->whenLoaded('GradeBookExternalMarks')),


        ];
    }
}
