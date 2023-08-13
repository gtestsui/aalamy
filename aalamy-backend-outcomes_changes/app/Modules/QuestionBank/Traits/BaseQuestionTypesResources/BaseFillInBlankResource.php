<?php

namespace Modules\QuestionBank\Traits\BaseQuestionTypesResources;



trait BaseFillInBlankResource
{

    /**
     * @return array
     *
     */
    public function baseResource(){
        return [
            'id' => (int)$this->id,
            'word' => $this->word,
            'order' => (int)$this->order,

        ];
    }

}
