<?php

namespace Modules\Quiz\Http\Resources;

use App\Http\Traits\PaginationResources;
use Illuminate\Http\Resources\Json\JsonResource;

class GetStudentsMarksResource extends JsonResource
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
            'student_id' => (int)$this->student_id,
            'fname' => $this->fname,
            'lname' => $this->lname,
            'final_mark' => (float)round($this->final_mark,2),

        ];
    }
}
