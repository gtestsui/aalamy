<?php

namespace Modules\QuestionBank\Traits\BaseQuestionTypesResources;



trait BaseOrderingResource
{


    /**
     * @return array
     *
     */
    public function baseResource(){
        return [
            'id' => $this->id,
            'text' => $this->text,
            'order' => (int)$this->order,

        ];
    }


}
