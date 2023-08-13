<?php

namespace Modules\QuestionBank\Traits\ModelRelations\LibraryQuestion;


use Modules\QuestionBank\Models\LibraryQuestion;
use Modules\QuestionBank\Models\LibraryQuestionMatchingLeftList;


trait MatchingRightListRelations
{

    //Relations
    public function Question(){
        return $this->belongsTo(LibraryQuestion::class,'library_question_id');
    }

    public function LeftList(){
        return $this->belongsTo(LibraryQuestionMatchingLeftList::class,'left_list_id');
    }

}
