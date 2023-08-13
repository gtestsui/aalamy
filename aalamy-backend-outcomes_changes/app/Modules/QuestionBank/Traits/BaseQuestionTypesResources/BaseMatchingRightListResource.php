<?php

namespace Modules\QuestionBank\Traits\BaseQuestionTypesResources;



trait BaseMatchingRightListResource
{

    /**
     * @return array
     *
     */
    public function baseResource(){
        return [
            'id' => $this->id,
            'left_list_id' => isset($this->left_list_id)
                ?(int)$this->left_list_id
                :null,
            'text' => $this->text,


        ];
    }


}
