<?php

namespace Modules\QuestionBank\Traits\BaseQuestionTypesResources;


trait BaseTrueFalseResource
{

    /**
     * @return array
     *
     */
    public function baseResource(){
        return [
            'id' => $this->id,
            'status' => (bool)$this->status,

        ];
    }

}
