<?php

namespace Modules\QuestionBank\Traits\BaseQuestionTypesResources;



trait BaseJumbleSentenceResource
{

    /**
     * @return array
     *
     */
    public function baseResource(){
        return [
            'id' => $this->id,
            'word' => $this->word,
            'order' => (int)$this->order,

        ];
    }

}
