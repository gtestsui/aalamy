<?php

namespace Modules\QuestionBank\Traits\BaseQuestionTypesResources;


trait BaseMatchingLeftListResource
{

    /**
     * @return array
     *
     */
    public function baseResource(){
        return [
            'id' => $this->id,
            'text' => $this->text,


        ];
    }

}
