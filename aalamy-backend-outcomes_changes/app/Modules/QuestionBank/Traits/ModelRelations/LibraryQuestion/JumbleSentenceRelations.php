<?php

namespace Modules\QuestionBank\Traits\ModelRelations\LibraryQuestion;


use Modules\QuestionBank\Models\LibraryQuestion;


trait JumbleSentenceRelations
{

    //Relations
    public function Question(){
        return $this->belongsTo(LibraryQuestion::class,'library_question_id');
    }

}
