<?php

namespace Modules\Quiz\Traits\ResourceSharedData;


use Modules\Quiz\Http\Resources\QuizResource;

trait QuizQuestionResourceSharedDataTrait
{

    public function getSharedData(){
        return [
            'id' => $this->id,
            'quiz_id' => isset($this->quiz_id)?(int)$this->quiz_id:$this->quiz_id,
            'question_id' => isset($this->question_id)?(int)$this->question_id:$this->question_id,
            'mark' => round($this->mark,2),


            'deleted' => (bool)$this->deleted,
            'deleted_at' => $this->deleted_at,


            'quiz' => new QuizResource($this->whenLoaded('Quiz')),
        ];
    }

}
