<?php

namespace Modules\QuestionBank\Traits\BaseQuestionTypesResources;



trait BaseMultiChoiceResource
{


    /**
     * @return array
     *
     */
    public function baseResource(){
        return [
            'id' => $this->id,
            'choice' => $this->choice,
            'status' => (bool)$this->status,

        ];
    }

}
