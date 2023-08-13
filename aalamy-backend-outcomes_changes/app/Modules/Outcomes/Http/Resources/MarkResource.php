<?php

namespace Modules\Outcomes\Http\Resources;

use App\Http\Traits\PaginationResources;
use Illuminate\Http\Resources\Json\JsonResource;
use Modules\Level\Http\Resources\SubjectResource;

class MarkResource extends JsonResource
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
            'student_studying_information_id' => isset($this->student_studying_information_id)?(int)$this->student_studying_information_id:$this->student_studying_information_id,
            'subject_id' => isset($this->subject_id)?(int)$this->subject_id:$this->subject_id,
            'level_subject_id' => isset($this->level_subject_id)?(int)$this->level_subject_id:$this->level_subject_id,

            'its_one_field' => isset($this->its_one_field)
                ?(bool)$this->subject_id
                :null,
            'verbal' => isset($this->verbal)
                ?(float)$this->verbal
                :null,
            'jobs_and_worksheets' => isset($this->jobs_and_worksheets)
                ?(float)$this->jobs_and_worksheets
                :null,
            'activities_and_Initiatives' => isset($this->activities_and_Initiatives)
                ?(float)$this->activities_and_Initiatives
                :null,
            'quiz' => isset($this->quiz)
                ?(float)$this->quiz
                :null,
            'exam' => isset($this->exam)
                ?(float)$this->exam
                :null,
            'final_mark' => isset($this->final_mark)
                            ?(float)$this->final_mark
                            :null,

            'deleted' => (bool)$this->deleted,
            'deleted_at' => $this->deleted_at,

            'subject' => new SubjectResource($this->whenLoaded('Subject')),
            'student_studying_information' => new StudentStudyingInformationResource($this->whenLoaded('StudentStudyingInformation')),


        ];
    }
}
