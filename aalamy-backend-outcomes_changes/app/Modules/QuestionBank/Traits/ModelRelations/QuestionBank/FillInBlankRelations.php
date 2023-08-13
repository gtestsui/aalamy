<?php

namespace Modules\QuestionBank\Traits\ModelRelations\QuestionBank;


use Modules\QuestionBank\Models\QuestionBank;


trait FillInBlankRelations
{

    //Relations
    public function Question(){
        return $this->belongsTo(QuestionBank::class,'question_id');
    }

}
