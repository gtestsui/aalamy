<?php

namespace Modules\QuestionBank\Traits\BaseQuestionTypesResources;



trait BaseFillTextResource
{

    /**
     * @return array
     *
     */
    public function baseResource(){
        return [
            'id' => (int)$this->id,
            'text' => $this->text,

        ];
    }

}
