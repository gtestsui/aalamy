<?php

namespace Modules\Quiz\Http\Resources;

use App\Http\Traits\PaginationResources;
use Illuminate\Http\Resources\Json\JsonResource;

class MyFinishedQuizzesWithMarksResource extends JsonResource
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
            'quiz_id' => (int)$this->quiz_id,
            'name' => $this->name,
            'prevent_display_answers' => (bool)$this->prevent_display_answers,
            'start_date' => $this->start_date,
            'end_date' => $this->end_date,
            'time' => (int)$this->time,
            'level_name' => $this->level_name,
            'subject_name' => $this->subject_name,
            'unit_name' => $this->unit_name,
            'lesson_name' => $this->lesson_name,
            'owner' => $this->owner_fname.' '.$this->owner_lname,
            'school_name' => $this->school_name,
            'quiz_mark' => (int)$this->quiz_mark,
            'full_mark' => (float)round($this->full_mark,2),
        ];
    }
}
